#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>
#include <string.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <netdb.h>
#include <time.h>

void error(const char *msg)
{
  perror(msg);
  exit(0);

}

int main(int argc, char *argv[])
{
	int sockfd,portno,n, ID, i;
	struct sockaddr_in serv_addr;
	struct hostent *server;
    char userInput[1024], target[1024], task[1024];
	char buffer[256];
	char colon = ';';
	if (argc < 4)
	{
		fprintf(stderr, "usage %s hostname port\n" , argv[0]);
		exit(0);
	}
	portno = atoi(argv[2]);
   	ID = atoi(argv[3]);
	sockfd = socket(AF_INET ,SOCK_STREAM,0 );
	if(sockfd < 0)
		error("ERROR opening socket");
	    server = gethostbyname(argv[1]);
	    if (server == NULL)

	    {
	    	fprintf(stderr, "Error , no such host" );
	    }

	    bzero((char *) &serv_addr , sizeof(serv_addr));
	    serv_addr.sin_family = AF_INET;
	    bcopy((char *) server->h_addr ,(char *) &serv_addr.sin_addr.s_addr ,server->h_length);
	    serv_addr.sin_port = htons(portno);
	    if(connect(sockfd , (struct sockaddr *) &serv_addr , sizeof(serv_addr))<0)
	        error("Connection Failed");
		char ans[1024];
		int  choice ;
        write(sockfd, &ID, sizeof(int));
		bzero(buffer,256);
	  	n = read(sockfd,buffer,255);
	  	if(n < 0)
	      	error("ERROR reading from socket");
		printf("My ID - %d\n",ID);
		printf("Server - %s\n",buffer);
		gets(userInput);
		// scanf("%s" ,str1); 
		send(sockfd, userInput , 1024 , 0);

		//send(sockfd, ID, 200,0);
		// printf("%s\n",userInput);

		/* n = read(sockfd,buffer,255);
		if(n < 0)
		error("ERROR reading from socket");
		printf("Server - %s\n",buffer);
		scanf("%d" ,&choice);
		write(sockfd, &choice, sizeof(int));	

		if(choice == 5)
		goto Q;*/

	  	//read(sockfd , &ans , sizeof(char *));

	  	//If a single job is sent
	  	if (strchr(userInput,colon)==NULL){
		  	recv(sockfd, ans , 1024 , 0);
		  	printf("Server- The Result is: %s\n", ans);
	  	}
	  	else{ //If multiple jobs are sent
            const char delimeter[2] = ";";
            char * token = strtok(userInput,delimeter);
			char *store[1024];
            
            for(i=0;(token != NULL); (token = strtok(NULL,delimeter))){
            //Pointer array to store the individual jobs
                store[i]=token;
                i++;  
            }
            store[i]=NULL;
            for (i=0;store[i]!=NULL;i++){
	  			recv(sockfd, ans , 1024 , 0);
		  		printf("Server- Result (%d) is: %s\n",i+1,ans);
	  		}
	  	}

	  /*	if(choice != 5)
	  		  goto S;*/


	  	/*Q :
	  	printf("You have selected to exit. Exit successful.");*/

		close(sockfd);
		return 0;

}
	

