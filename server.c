#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdbool.h>
#include <unistd.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <sys/socket.h>
#include <time.h>

void error (const char *msg)
{
   perror(msg);
   exit(1);
}

char *doDouble(char target[],char answer[]);

char *doReverse(char target[], char answer[]);

void reverse(char target[], int index, int size);//Reverse aid/dummy

char *doDelete(char target[],char ans[],char positions[],char positions_dup[]);

char *doReplace(char target[],char answer[],char positions[],char positions_dup[]);

char *doEncrypt(char targetstring[],char encryptedResult[]);

char *doDecrypt(char targetstring[],char decryptedResult[]);

bool array_lookup(int value, int *array, int size);

char * convertToString(char ch);

char *sendError(char answer[]);

int main(int argc ,char *argv[])
{
    if(argc < 2)
    {
        fprintf(stderr ,"port No. not provided, program terminated\n");
        exit(1);
    }
    int sockfd ,newsockfd ,portno ,n;
    char buffer[255];
    char userInput[1024], target[1024], task[1024],positions[100],positions_dup[100];

  
    char colon = ';';
                
    struct sockaddr_in serv_addr  , cli_addr;
    socklen_t clilen;
  
    sockfd = socket(AF_INET , SOCK_STREAM ,0);
    if(sockfd < 0)
    {
       error("Error opening Socket.");
    }

    bzero ((char *) &serv_addr , sizeof(serv_addr));
    portno = atoi(argv[1]);

    serv_addr.sin_family = AF_INET;
    serv_addr.sin_addr.s_addr = INADDR_ANY;
    serv_addr.sin_port = htons(portno);

    if(bind(sockfd , (struct sockaddr *) &serv_addr ,sizeof(serv_addr)) < 0)
    error("Binding Failed");

    listen(sockfd ,5);
    clilen = sizeof(cli_addr);
    int pid;
    char ans[1024],jobID[4];
    int i,j, ID;
    double job_duration;


    while (1) {
        newsockfd = accept(sockfd ,(struct sockaddr *) &cli_addr ,&clilen);
       
        if (newsockfd < 0)
            error("Error on accept");
        pid = fork();
        if (pid < 0) {
            error("ERROR in new process creation");
        }
        if (pid == 0) {
          close(sockfd);
          

            n = write(newsockfd,"Enter your job(s) :",strlen("Enter your job(s)"));
            if(n < 0)
                error("ERROR writing to socket");
            read(newsockfd, &ID, sizeof(int));
          
            recv(newsockfd, userInput , 1024 , 0);
            
            //Getting time of the job
            time_t current_time;
            char day[10],month[10],tym[10];
            int date, year;
            time(&current_time);
    
            sscanf ((ctime(&current_time)),"%s %s %d %s %d",day,month,&date,tym,&year);

            //printf("Client - my string is :%s\n", userInput);

            //When a single job is received
            if (strchr(userInput,colon)==NULL){
                sscanf(userInput, "%s %s %s", task, target,positions);
                sscanf(positions,"%s",positions_dup);
                if (strlen(target)>50)
                {
                    FILE * black;
                    black = fopen ("blacklist.txt", "a");
                    fprintf(black,"%d,%s,%s,%d/%s/%d\n",ID,task,tym,date,month,year);
                    fclose(black);
                    strcpy(ans,"Your task has been blacklisted because it is above fifty characters."); 
                    printf("Job blacklisted\n");
                }else{
/*Double*/                    
                    if (strcmp(task,"double")==0){
                        //Begin timing when the job starts
                        clock_t begin = clock();
                        doDouble(target,ans);
                        strcpy(jobID,"j01");
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        
                        printf("The result is '%s'\n",ans);
                    }
/*End of Double*/
/*Reverse*/
                    else if (strcmp(task,"reverse")==0){
                        clock_t begin = clock();
                        strcpy(jobID,"j02");
                        doReverse(target,ans);
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        printf("The result is '%s'\n",ans);
                    }
/*End of Reverse*/
/*Delete*/
                    else if (strcmp(task,"delete")==0){
                        clock_t begin = clock();
                        strcpy(jobID,"j03");
                        doDelete(target,ans,positions,positions_dup);
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        printf("The result is '%s'\n",ans);
                    }
/*End of Delete*/                   
/*Replace*/                 
                    else if (strcmp(task,"replace")==0){
                        clock_t begin = clock();
                        strcpy(jobID,"j06");                                        
                        doReplace(target,ans,positions,positions_dup);
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        printf("The result is '%s'\n",ans);
                    }
/*End of Replace*/
/*Encrypt*/
                    else if (strcmp(task,"encrypt")==0){
                        clock_t begin = clock();
                        strcpy(jobID,"j04");
                        doEncrypt(target,ans);
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        printf("The result is '%s'\n",ans);
                    }
/*End of Encrypt*/
/*Decrypt*/
                    else if (strcmp(task,"decrypt")==0){
                        clock_t begin = clock();
                        strcpy(jobID,"j05");
                        doDecrypt(target,ans);
                        clock_t end = clock();
                        job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
                        printf("The result is '%s'\n",ans);
                    }
/*End of Decrypt*/
/*Error*/                       
                    else{
                        sendError(ans);
                    }
	                if (strcmp("Your task has an error; Check it and try again",ans)==0){
	                    printf("There is an error\n");
	                }else{
	                    FILE *ready;
	                    ready = fopen ("ready_jobs.txt", "a");
	                    fprintf(ready, "%d,%s,%s,%s,%d/%s/%d,%.6f\n",ID,jobID,task,tym,date,month,year,job_duration); 
	                    fclose(ready);
                	}
                }

                send(newsockfd, ans , 1024 , 0);

            }//End of if job is single
            
            else{
                const char delimeter[2] = ";";
                char * token = strtok(userInput,delimeter);
                char *store[1024];
                                
                for(i=0;(token != NULL); (token = strtok(NULL,delimeter))){
                //Pointer array to store the individual jobs
                    store[i]=token;
                    i++;  
                }
                int no_storeItems = i;
                store[i]=NULL;
             
                //Sorting the multiple jobs according to the length of the target
                char space = ' ';
                int c,d,length_former,length_next;
                char * swap;
                //printf("%d\n",no_storeItems);

                //Swapping between two jobs of greater and less length 
                for (c = 0; c < (no_storeItems - 1); c++){
                    for (d = 0; d < (no_storeItems - c - 1); d++){
                        length_former = strlen((strchr(store[d],space)+1)); //get the length of the string in the position after the space at the index d
                        length_next  = strlen((strchr(store[d+1],space)+1)); //get the length of the string in the position after the space at the index after the previous
                        if (length_former > length_next){
                            swap = store[d];
                            store[d] = store[d+1];
                            store[d + 1] = swap;
                        }
                    }
                }

                //Putting  a replace in the last position
                for (c = 0; c < (no_storeItems - 1); c++){
                    for (d = 0; d < (no_storeItems - c - 1); d++){
                        if (strncmp(store[d],"replace",7)==0){
                            swap = store[d];
                            store[d] = store[d+1];
                            store[d + 1] = swap;
                        }    
                    }
                }

                //For each task stored in store
                for (i=0;store[i]!=NULL;i++){
                    sscanf( store[i],"%s %s %s", task,target,positions);
                    sscanf(positions,"%s",positions_dup);
                    //Sending to blacklist
                    if (strlen(target)>50){
                        FILE * black;
                        black = fopen ("blacklist.txt", "a");
                        fprintf(black,"%d,%s,%s,%d/%s/%d\n",ID,task,tym,date,month,year);
                        fclose(black);
                        printf("Job blacklisted\n"); 
                        strcpy(ans,"Your task has been blacklisted because it is above fifty characters.");
                        send(newsockfd, ans , 1024 , 0);
                    }
                    //Error handling: When the target string is not provided
                    else if(strlen(target)==0){
                    	strcpy(ans,"String to work on not provided. Please try again");
                		send(newsockfd, ans , 1024 , 0);
                    }
                    //Checking whether the task is valid
                    else if( (strcmp(task,"double")==0) || (strcmp(task,"reverse")==0) || (strcmp(task,"delete")==0) || (strcmp(task,"replace")==0) ||(strcmp(task,"encrypt")==0) || (strcmp(task,"decrypt")==0) ){
                        FILE *busy;
                        busy = fopen ("busy_list.txt", "a");
                        fprintf(busy,"%d %s %s %s %s %s %d/%s/%d\n",ID,task,target,positions,positions_dup,tym,date,month,year);
                        fclose(busy);
                    }

                    //Error message if the task is invalid
                    else{
                    	strcpy(ans,"There is an error. Please try again");
                		send(newsockfd, ans , 1024 , 0);
                    }    
                }
                //Checking for the number of lines 
                #define MAX_SIZE 1024
                FILE *busy;
                char line[MAX_SIZE];
                int num_lines = 0; /*Number of lines*/

                busy = fopen ("busy_list.txt", "r");
                while(fgets(line, sizeof(line), busy) != NULL){
                    num_lines++;
                }
                rewind(busy);
                fclose(busy);
                //Looping through all lines from busy_list
                if (num_lines > 0){
	        		for (i = 0; i < num_lines; i++){
	                    char file_content[100];
	                    FILE *busy, *duplicate;
	                    busy=fopen("busy_list.txt","rt");
	                    fscanf(busy,"%d %s %s %s %s\n",&ID,task,target,positions,positions_dup);
	                    fclose(busy);
	                    


	/*Double*/          if (strcmp(task,"double")==0){ 
                            clock_t begin = clock();
	                        doDouble(target,ans); 
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        strcpy(jobID,"j01");
	                        printf("The result is '%s'\n",ans);
	                    }

	/*End of Double*/
	/*Reverse*/
	                    else if (strcmp(task,"reverse")==0){ 
                            clock_t begin = clock();
	                        strcpy(jobID,"j02");
	                        doReverse(target,ans); 
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        printf("The result is '%s'\n",ans);
	                    }
	/*End of Reverse*/
	/*Delete*/
	                    
	                    else if (strcmp(task,"delete")==0){ 
                            clock_t begin = clock();
	                        strcpy(jobID,"j03");
	                        doDelete(target,ans,positions,positions_dup);
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        printf("The result is '%s'\n",ans);
	                    }    
	/*End of Delete*/                   
	/*Replace*/ 

	                    else if (strcmp(task,"replace")==0){ 
                            clock_t begin = clock();
	                        strcpy(jobID,"j06");                                        
	                        doReplace(target,ans,positions,positions_dup);
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        printf("The result is '%s'\n",ans);
	                    }

	/*End of Replace*/                   
	/*Encrypt*/ 
	                    else if (strcmp(task,"encrypt")==0){ 
                            clock_t begin = clock();
	                        strcpy(jobID,"j04");
	                        doEncrypt(target,ans); 
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        printf("The result is '%s'\n",ans);
	                    }
	/*End of Encrypt*/
	/*Decrypt*/
	                    else if (strcmp(task,"decrypt")==0){ 
                            clock_t begin = clock();
	                        strcpy(jobID,"j05");
	                        doDecrypt(target,ans); 
                            clock_t end = clock();
                            job_duration = (double)(end - begin) / CLOCKS_PER_SEC;
	                        printf("The result is '%s'\n",ans);
	                    }
	//End of Decrypt                            

                        FILE *ready;
                        ready = fopen ("ready_jobs.txt", "a");
                        fprintf(ready, "%d,%s,%s,%s,%d/%s/%d,%.6f\n",ID,jobID,task,tym,date,month,year,job_duration); 
                        fclose(ready);
	                    

	                    send(newsockfd, ans , 1024 , 0);

	                    //Delete first line after reading it
	                    int del_lineNo = 1;
	                    int line_number = 0;
	                    busy = fopen ("busy_list.txt", "r");
	                    duplicate = fopen("duplicate_file.txt","w");
	                    while( fgets(file_content, 99, busy) != NULL ){
	                        line_number++;
	                        if(line_number != del_lineNo){
	                            fputs(file_content,duplicate);
	                        }
	                    }
	                    fclose(busy);
	                    fclose(duplicate);
	                    remove("busy_list.txt");
	                    rename("duplicate_file.txt", "busy_list.txt");

	                }//Loop to pick single job when multiple

                }//if busy_list has content
                else{
                	printf("Nothing to process\n");
                }//If busy list has no content

            }//End of If single Job
            
            close(newsockfd);
            exit(EXIT_SUCCESS);
        }//if pid == 0
        else{
            close(newsockfd);     
        }
        
    }//Socket Loop
    return 0;

}//End of main                


/*Global Functions*/

//Search through int array function
bool array_lookup(int value, int *array, int size){
    int i;
    for (i=0; i < size; i++) {
        if (array[i] == value)
            return true;
    }
    return false;
}//End of search through int array function

//Convert charcater to string Function
char * convertToString(char ch) {
    char *string = malloc(2 * sizeof (char));
    string[0] = ch;
    string[1] = '\0';
    return string;
}//End of convert charcater to string Function

//Double Function
char *doDouble(char target[],char answer[]){
    strcat(target,target);
    strcpy(answer, target);
    return answer;
}//End of Double Function

//Reverse Function
char *doReverse(char target[], char answer[]){
    int size = strlen(target);
    reverse(target, 0, size - 1);
    strcpy(answer, target);
    return answer;
}//End of Reverse Function


//Reverse Dummy
void reverse(char target[], int index, int size){      
        char temp;
        temp = target[index];
        target[index] = target[size - index];
        target[size - index] = temp;
        if (index == size / 2){    
            return;
        }
        reverse(target, index + 1, size);
}//End of Reverse Dummy


//Delete Function
char *doDelete(char target[],char ans[],char positions[],char positions_dup[]){
    char **temp_del,final[100];
    int array_positions[100],size,i,j;
    const char delimeter_del[2] = ",";
    char * token_del = strtok(positions,delimeter_del);
    for(i=0;(token_del != NULL);(token_del = strtok(NULL,delimeter_del))){ //findng how many items are in picked at the end of the loop
        i++;
    }

    const int MAX = i;
    token_del = strtok(positions_dup,delimeter_del); //token_del is re-initialized using the duplicate
    char *positionArray[MAX];   // the array is assigned the actual number of items
    
    for(j=0;(token_del != NULL);(token_del = strtok(NULL,delimeter_del))){
        positionArray[j]=token_del;
        j++;
    }

    positionArray[j] = NULL;
    size = sizeof(positionArray);
    temp_del = malloc(size);
    memcpy(temp_del,positionArray,sizeof(positionArray));
    memset(final, '\0', sizeof(final));    //small change here
    for (i = 0; i < MAX; i++)
    {
        array_positions[i] = atoi(temp_del[i]);
    
    }
    for(i=0;i<strlen(target);i++){
        if((array_lookup(i+1, array_positions, MAX ))==0){
            
            char *finalPtr = convertToString(target[i]);
            strcat(final,finalPtr);
        }
    }
    strcpy(ans,final);
    return ans;
}//End of Delete Function

//Replace Function
char *doReplace(char target[],char answer[],char positions[],char positions_dup[]){
    int sizeR,num_location[100],i,j;
    char final[100],**tempR_num,**tempR_char;
    const char delimeter_rep[2] = ",";
    char * token_rep = strtok(positions,delimeter_rep);
    for(i=0;(token_rep != NULL);(token_rep = strtok(NULL,delimeter_rep))){ //findng how many items are in picked at the end of the loop
        i++;
    }
    
    const int MAX_rep = i;
    token_rep = strtok(positions_dup,delimeter_rep); //token_rep is re-initialized using the duplicate
    char *locationArray[MAX_rep];
    for (j=0;(token_rep != NULL);(token_rep = strtok(NULL,delimeter_rep))){
        locationArray[j] = token_rep;
        j++;
    }
    locationArray[j] = NULL;
    
    sizeR = sizeof(locationArray);
    
    tempR_num = malloc(sizeR);
    tempR_char = malloc(sizeR);
    
    memset(final, '\0', sizeof(final));
    
    for (i = 0; i < MAX_rep; i++)
    {
        char * token_rep2 = strtok(locationArray[i],"-");   //Second token_rep dividing between either sides of "-" is declared
        tempR_num[i] = token_rep2;                          //The integers are stored in the tempR_num pointer
        token_rep2 = strtok(NULL,"-");
        tempR_char[i] = token_rep2;                         //The characters are stored in the tempR_char pointer
    }
    tempR_char[i] = NULL; 
    tempR_num[i] = NULL;
    //Convert the integer positions in tempR_num from charcaters to integers and store into new array
    for (j = 0; j < MAX_rep; j++)
    {
        num_location[j] = atoi(tempR_num[j]);
    }
    for(i=0;i<strlen(target);i++){
        for (j=0; j<MAX_rep; j++){
            if ((array_lookup(i+1,num_location,MAX_rep))==0){
                char *finalPtr = convertToString(target[i]);
                strcat(final,finalPtr);
                break;
            }
            else if (i+1 == num_location[j]){
                strcat(final,tempR_char[j]);
                break;
            }
        }
    }

    strcpy(answer,final);

    return answer;
}//End of Replace Function

//Encrypt Function
char *doEncrypt(char targetstring[],char encryptedResult[]){
    int outter,inner;
    char container[54][18], alphabets[]={' ','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'};
    strcpy(container[0],"#"); strcpy(container[1],"A"); 
    strcpy(container[2],"B"); strcpy(container[3],"C"); 
    strcpy(container[4],"D");strcpy(container[5],"E"); 
    strcpy(container[6],"F"); strcpy(container[7],"G"); 
    strcpy(container[8],"H"); strcpy(container[9],"I");
    strcpy(container[10],"1iA0"); strcpy(container[11],"1jB1"); 
    strcpy(container[12],"1kC2"); strcpy(container[13],"1lD3");
    strcpy(container[14],"1mE4"); strcpy(container[15],"1nF5"); 
    strcpy(container[16],"1oG6"); strcpy(container[17],"1pH7");
    strcpy(container[18],"1qI8"); strcpy(container[19],"1r1Ai09");  
    strcpy(container[20],"2s1Bj10"); strcpy(container[21],"2t1Ck21");
    strcpy(container[22],"2u1Dl32");  strcpy(container[23],"2v1Em43"); 
    strcpy(container[24],"2w1Fn54"); strcpy(container[25],"2x1Go65");
    strcpy(container[26],"2y1Hp76"); strcpy(container[27],"2z1Iq87"); 
    strcpy(container[28],"2a1r1Ai098"); strcpy(container[29],"2b2s1Bj109");
    strcpy(container[30],"3c2t1Ck210"); strcpy(container[31],"3d2u1Dl321"); 
    strcpy(container[32],"3e2v1Em432"); strcpy(container[33],"3f2w1Fn543");
    strcpy(container[34],"3g2x1Go654");  strcpy(container[35],"3h2y1Hp765"); 
    strcpy(container[36],"3i2z1Iq876"); strcpy(container[37],"3j2a1rA1i0987");
    strcpy(container[38],"3k2b2sB1j1098"); strcpy(container[39],"3l3c2tC1k2109"); 
    strcpy(container[40],"4m3d2uD1l3210"); strcpy(container[41],"4n3e2vE1m4321"); 
    strcpy(container[42],"4o3f2wF1n5432"); strcpy(container[43],"4p3g2xG1o6543");
    strcpy(container[44],"4q3h2yH1p7654"); strcpy(container[45],"4r3i2zI1q8765"); 
    strcpy(container[46],"4s3j2a1rA1i09876"); strcpy(container[47],"4t3k2b2sB1j10987");
    strcpy(container[48],"4u3l3c2tC1k21098"); strcpy(container[49],"4v4m3d2uD1l32109"); 
    strcpy(container[50],"5w4n3e2vE1m43210");strcpy(container[51],"5x4o3f2wF1n54321"); 
    strcpy(container[52],"5y4p3g2xG1o65432");
    strcpy(encryptedResult," ");
    for(outter=0;outter<strlen(targetstring);outter++){
        for(inner=0;inner<sizeof(alphabets);inner++){
            if(targetstring[outter]==alphabets[inner]){
                strcat(encryptedResult,container[inner]);
            }
        }
    }
    return encryptedResult ;
}//End of encrypt function


//Decrypt Function
char *doDecrypt(char targetstring[],char decryptedResult[]){
    int x,y,reset=0,next=0,found_true=0,start=0,end=0;
    char temp_decrypt[28];
    char container[54][18],alphabets[]={' ','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'};
    strcpy(container[0],"#"); strcpy(container[1],"A"); 
    strcpy(container[2],"B"); strcpy(container[3],"C"); 
    strcpy(container[4],"D");strcpy(container[5],"E"); 
    strcpy(container[6],"F"); strcpy(container[7],"G"); 
    strcpy(container[8],"H"); strcpy(container[9],"I");
    strcpy(container[10],"1iA0"); strcpy(container[11],"1jB1"); 
    strcpy(container[12],"1kC2"); strcpy(container[13],"1lD3");
    strcpy(container[14],"1mE4"); strcpy(container[15],"1nF5"); 
    strcpy(container[16],"1oG6"); strcpy(container[17],"1pH7");
    strcpy(container[18],"1qI8"); strcpy(container[19],"1r1Ai09");  
    strcpy(container[20],"2s1Bj10"); strcpy(container[21],"2t1Ck21");
    strcpy(container[22],"2u1Dl32");  strcpy(container[23],"2v1Em43"); 
    strcpy(container[24],"2w1Fn54"); strcpy(container[25],"2x1Go65");
    strcpy(container[26],"2y1Hp76"); strcpy(container[27],"2z1Iq87"); 
    strcpy(container[28],"2a1r1Ai098"); strcpy(container[29],"2b2s1Bj109");
    strcpy(container[30],"3c2t1Ck210"); strcpy(container[31],"3d2u1Dl321"); 
    strcpy(container[32],"3e2v1Em432"); strcpy(container[33],"3f2w1Fn543");
    strcpy(container[34],"3g2x1Go654");  strcpy(container[35],"3h2y1Hp765"); 
    strcpy(container[36],"3i2z1Iq876"); strcpy(container[37],"3j2a1rA1i0987");
    strcpy(container[38],"3k2b2sB1j1098"); strcpy(container[39],"3l3c2tC1k2109"); 
    strcpy(container[40],"4m3d2uD1l3210"); strcpy(container[41],"4n3e2vE1m4321"); 
    strcpy(container[42],"4o3f2wF1n5432"); strcpy(container[43],"4p3g2xG1o6543");
    strcpy(container[44],"4q3h2yH1p7654"); strcpy(container[45],"4r3i2zI1q8765"); 
    strcpy(container[46],"4s3j2a1rA1i09876"); strcpy(container[47],"4t3k2b2sB1j10987");
    strcpy(container[48],"4u3l3c2tC1k21098"); strcpy(container[49],"4v4m3d2uD1l32109"); 
    strcpy(container[50],"5w4n3e2vE1m43210");strcpy(container[51],"5x4o3f2wF1n54321"); 
    strcpy(container[52],"5y4p3g2xG1o65432");
    do{
        reset=0; 
        for(x=start;x<=end;x++){ //start = 0   end
            sprintf(&temp_decrypt[reset],"%c",targetstring[x]); 
            reset++; 
        }
        for(y=1;y<=52;y++){ 
            if(!strcmp(temp_decrypt,container[y])){ 
                sprintf(&decryptedResult[next],"%c",alphabets[y]);  
                next++; 
                found_true=1; 
                start=end+1; 
                end=end+1; 
                bzero(temp_decrypt,0); 
                break; 
            } 
        }
        if(found_true==0){
            end=end+3; 
        }else{
            found_true=0;
        }
        if(targetstring[end]==' ' || end >= strlen(targetstring)){
            break; 
        }
    }while(found_true==0);
    return decryptedResult;
}//End of decrypt funcion


//Task error function
char *sendError(char answer[]){
    strcpy(answer,"Your task has an error; Check it and try again");
    return answer;
}    