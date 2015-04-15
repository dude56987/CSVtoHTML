#! /usr/bin/python
import urllib2,os,sys,random,datetime,time
########################################################################
def loadFile(fileName):
	try:
		#print "Loading :",fileName
		fileObject=open(fileName,'r');
	except:
		print "Failed to load :",fileName
		return False
	fileText=''
	lineCount = 0
	for line in fileObject:
		fileText += line
		#sys.stdout.write('Loading line '+str(lineCount)+'...\r')
		lineCount += 1
	#print "Finished Loading :",fileName
	fileObject.close()
	if fileText == None:
		return False
	else:
		return fileText
	#if somehow everything fails return fail
	return False
########################################################################
def writeFile(fileName,contentToWrite):
	# figure out the file path
	filepath = fileName.split(os.sep)
	filepath.pop()
	filepath = os.sep.join(filepath)
	# check if path exists
	if os.path.exists(filepath):
		try:
			fileObject = open(fileName,'w')
			fileObject.write(contentToWrite)
			fileObject.close()
			#print 'Wrote file:',fileName
		except:
			print 'Failed to write file:',fileName
			return False
	else:
		print 'Failed to write file, path:',filepath,'does not exist!'
		return False
########################################################################
def downloadFile(fileAddress):
	try:
		print "Downloading :",fileAddress
		downloadedFileObject = urllib2.urlopen(str(fileAddress))
	except:
		print "Failed to download :",fileAddress
		return False
	lineCount = 0
	fileText = ''
	for line in downloadedFileObject:
		fileText += line
		sys.stdout.write('Loading line '+str(lineCount)+'...\r')
		lineCount+=1
	downloadedFileObject.close()
	print "Finished Loading :",fileAddress
	return fileText
########################################################################
def phaseLine(item):
	outputFileText=''
	if len(item) > 0:
		if item == '#':
			#print two blank lines
			outputFileText+=(('\t</br>'*2)+'\n')
		elif item[:12] == "#BACKGROUND=":
			# do nothing
			return ''
		elif item[0] == '#':
			# print a header
			outputFileText+=('\t<h1 class="header">'+item[1:]+'</h1>\n')
		else:
			# print a item
			outputFileText+=('\t<div class="menu_item">\n\t\t'+item+'\n\t</div>\n')
	return outputFileText
########################################################################
# Main function
########################################################################
def main():
	if '-c' in sys.argv:
		if os.path.exists('/tmp/refreshGlue'):
			# clear the file
			os.system('rm -f /tmp/refreshGlue')
			# run the script
			pass
		else:
			# exit if no refresh file is set
			exit()
	# load the config file into a string
	config = loadFile('/etc/glue.cfg')
	config = config.split('\n')
	temp = []
	for item in config:
		temp.append(item.split('='))
	config = temp
	# preset variable defaults if they are not set in config file
	config_stylesheetLocation=''
	config_outputLocation='~/'
	config_breakfastStartTime='0'
	config_lunchStartTime='10'
	config_dinnerStartTime='15'
	config_lateMealStartTime='19'
	# set varables from config file for program
	print ("#"*80)
	print ("Reading config file...")
	print ("#"*80)
	for setting in config:
		if len(setting)>1:
			if setting[0][0] == '#':
				# the line is a comment, ignore it
				pass
			# config file location
			if setting[0] == 'location':
				config_location=setting[1].replace(' ','').replace('\r','\n')
				print ("location="+config_location+';')
			elif setting[0] == 'outputLocation':
				config_outputLocation=setting[1].replace(' ','').replace('\r','\n')
				print ("outputLocation="+config_outputLocation+';')
			# stylesheet location
			elif setting[0] == 'stylesheetLocation':
				config_stylesheetLocation=setting[1].replace(' ','')
				print ("stylesheetLocation="+config_stylesheetLocation+';')
			# BREAKFAST 
			elif setting[0] == 'breakfastStartTime':
				config_breakfastStartTime=int(setting[1].replace(' ',''))
				print ('breakfastStartTime='+str(config_breakfastStartTime)+';')
			# LUNCH
			elif setting[0] == 'lunchStartTime':
				config_lunchStartTime=int(setting[1].replace(' ',''))
				print ('lunchStartTime='+str(config_lunchStartTime)+';')
			# DINNER
			elif setting[0] == 'dinnerStartTime':
				config_dinnerStartTime=int(setting[1].replace(' ',''))
				print ('dinnerStartTime='+str(config_dinnerStartTime)+';')
			# LATE MEAL 
			elif setting[0] == 'lateMealStartTime':
				config_lateMealStartTime=int(setting[1].replace(' ',''))
				print ('lateMealStartTime='+str(config_lateMealStartTime)+';')
			else:
				print 'Unknown config option: ',setting
	# Seprate output with lines
	# load the file into a string depending on if its online or offline
	if 'http' in config_location:
		data = downloadFile(config_location)
	else:
		data = loadFile(config_location)
	data = data.replace('\r','\n')
	print ("#"*80)
	print ("Printing menu data...")
	print ("#"*80)
	print data
	tempData=''
	data.split('\n')
	for item in data:
		if item[0] == '0':
			tempData+=item[1:]
		else:
			tempData+=item
		tempData+='\n'
	data=tempData	
	# year-month-day is returned so convert to month/day/year
	todayDate = datetime.date.today().isoformat().split('-')
	tempDayDate=todayDate[2]
	if tempDayDate[0] == '0':
		# remove leading zeros from day in date field
		tempDayDate=tempDayDate[1:]
	tempMonthDate=todayDate[1]
	if tempMonthDate[0] == '0':
		# remove leading zeros from month in date field
		tempMonthDate=tempMonthDate[1:]
	todayDate = tempMonthDate+'/'+tempDayDate+'/'+todayDate[0]
	# load the stylesheet
	styleSheet=loadFile(config_stylesheetLocation)
	# if load fails make variable into blank string for concat to work
	if styleSheet == False:
		styleSheet=''
	# load the header and footer files
	if os.path.exists('/usr/share/signage/default/head.html'):
		header = loadFile('/usr/share/signage/default/head.html')
		if header == False:
			header = ''
	else:
		header = ''
	if os.path.exists('/usr/share/signage/default/foot.html'):
		footer = loadFile('/usr/share/signage/default/foot.html')
		if footer == False:
			footer = ''
	else:
		footer = ''
	# print out content of the header and footer files to be added
	# header part	
	print ("#"*80)
	print ("Printing header content...")
	print ("#"*80)
	print (header)
	# footer part
	print ("#"*80)
	print ("Printing footer content...")
	print ("#"*80)
	print (footer)
	########################################################
	# split up the input file and split in into an array
	data= data.split('\n')
	backgroundImage = ''
	outputFileText = ''
	for line in data:
		splitline = line.split(',')
		if todayDate == splitline[0]:
			currentTime = time.localtime()
			# the fourth item in the object is hours in military time
			currentTime = int(currentTime[3])
			# hours in military time
			if (currentTime >= config_breakfastStartTime) and (currentTime < config_lunchStartTime):
			# checks if current time is between breakfast start and lunch start
				if splitline[1] == 'BREAKFAST':
					#Its BREAKFAST time
					for item in splitline[2:]:
						if item[:12] == "#BACKGROUND=":
							backgroundImage='backgrounds/extra/'
							backgroundImage+=item[12:]
						outputFileText+=phaseLine(item)
			elif (currentTime >= config_lunchStartTime) and (currentTime < config_dinnerStartTime):
			# checks if current time is between lunch start and dinner start
				if splitline[1] == 'LUNCH':
					#Its LUNCH time
					for item in splitline[2:]:
						if item[:12] == "#BACKGROUND=":
							backgroundImage='backgrounds/extra/'
							backgroundImage+=item[12:]
						outputFileText+=phaseLine(item)
			elif (currentTime >= config_dinnerStartTime) and (currentTime < config_lateMealStartTime):
			# checks if current time is between dinner start and latemeal start
				if splitline[1] == 'DINNER':
					#Its DINNER time
					for item in splitline[2:]:
						if item[:12] == "#BACKGROUND=":
							backgroundImage='backgrounds/extra/'
							backgroundImage+=item[12:]
						outputFileText+=phaseLine(item)
			elif (currentTime >= config_lateMealStartTime) and (currentTime <= 24):
			# checks if current time is between latemeal start and midnight start
				if splitline[1] == 'LATEMEAL':
					# if the user specifys latemeal
					for item in splitline[2:]:
						if item[:12] == "#BACKGROUND=":
							backgroundImage='backgrounds/extra/'
							backgroundImage+=item[12:]
						outputFileText+=phaseLine(item)
	# check current time based on month
	# below is month number variable
	if backgroundImage == '':
		monthPath = os.path.join("backgrounds/",str(time.localtime()[1]))
		fullMonthPath = os.path.join(config_outputLocation,monthPath)
		monthFiles = os.listdir(fullMonthPath)
		if len(monthFiles) == 0:
			backgroundImage = ''
		else:
			monthFile = monthFiles[int(random.randrange(0,len(monthFiles)))]
			backgroundImage = os.path.join(monthPath,monthFiles[random.randrange(0,(len(monthFiles)))])
	# add the watermark if it exists

	# default background image
	if backgroundImage == '':
		backgroundImage='backgrounds/extra/default.jpg'
	outputFileTextTop  = '<html style="background-image: url('+"'"+backgroundImage+"'"+')">\n'
	outputFileTextTop += '<head>\n<style>\n'+styleSheet+'\n</style>\n</head>\n'
	outputFileTextTop += '<body>\n'
	# Add the top of the page first since its on all outputs	
	outputFileTextTop += "<h1>TODAY'S MENU:</h1>"+'\n'
	outputFileTextTop += '<div class="date_area">\n'
	# close the webpage body out
	outputFileText = outputFileTextTop+header+outputFileText+footer
	outputFileText += '</body>\n</html>'
	# print the output webpage to the screen
	print ("#"*80)
	print ('Printing generated webpage...')
	print ("#"*80)
	print outputFileText
	print ("#"*80)
	# write the webpage to the output location as index.html
	if len(config_outputLocation)>1:
		writeFile(os.path.join(config_outputLocation,'index.html'),outputFileText)
########################################################################
main()
