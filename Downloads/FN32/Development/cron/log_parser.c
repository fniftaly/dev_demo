#include <stdio.h>
#include <string.h>
int main (int argc, char *argv[])
{
	FILE* pFile;
	char  tmp[256]={0x0};
	char* count_ptr;
	int que_msg_count, sent_msg_count, count;
	int offset1=strlen("Message Queue Count: ");
	int offset2=strlen("Messages Sent: ");

	que_msg_count=sent_msg_count=0;
	if (argc<2)
	{
		printf("Please specify a filename to be parsed\n");
		return -1;
	}


	if (!(pFile=fopen(argv[1],"rt")))
	{
		printf("Unable to open file %s\n", argv[1]);
		return -1;
	}

	printf("Parsing file %s\n", argv[1]);
	
        while(pFile!=NULL && fgets(tmp, sizeof(tmp),pFile)!=NULL)
        {
        	count_ptr=strstr(tmp, "Message Queue Count: " );
		if (count_ptr)
		{
			count_ptr+=offset1;
        		sscanf(count_ptr,"%d", &count);
			que_msg_count += count;
        	}
        	count_ptr=strstr(tmp, "Messages Sent: " );
		if (count_ptr)
		{
			count_ptr+=offset2;
        		sscanf(count_ptr,"%d", &count);
			sent_msg_count += count;
		}			
	}

	fclose(pFile);

	printf("Queued Messages:  %d\nSent Messages:    %d\nNumber of Errors: %d\nSuccess Rate:     %d%%\n", que_msg_count, sent_msg_count, (que_msg_count-sent_msg_count), (sent_msg_count*100/que_msg_count));
	return 0;
}
