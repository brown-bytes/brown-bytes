

#fpin = open('events-data.txt', 'r')  
#fpno = open('events-data0.txt', 'a')
#fpyes = open('events-data1.txt', 'a')  



fileoutpath0 = 'events-data-no-food.txt'
fileoutpath1 = 'events-data-yes-food.txt'
fileinpath = 'events-data.txt'  
with open(fileinpath, 'r') as fpin, open(fileoutpath0, 'a') as fpout0, open(fileoutpath1, 'a') as fpout1:  
	line = fpin.readline()
	cnt = 1
	while line:
		print(line+"\n")
		x = input("Food?")
		if(x == 'y'):
			print('YES CONFIRMED')
			fpout1.write(line)
		else:
			print('NO CONFIMED')
			fpout0.write(line)
		line = fpin.readline()
		cnt += 1
		print(cnt)
