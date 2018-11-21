# -*- coding: utf-8 -*-
"""
Created on Sun Nov 18 19:47:53 2018

@author: barne
"""

import datetime


shifts = [
        {'thu' : [{3 : [[25, 10, 00, 15, 00], #want the program to completely disregard this shfit
                        [24, 15, 00, 17, 00], 
                        [21, 9, 00, 10, 00]]}, 
                  {2 : [[28, 9, 00, 10, 00],
                        [16, 10, 30, 17, 00]]}]}, 
        {'fri' : [{3 : [[23, 10, 00, 16, 00]]}]},
        {'sun' : [{8 : [[22, 11, 47, 4, 13]]}]}]


cStart = datetime.timedelta(hours=9, minutes=00)
cEnd = datetime.timedelta(hours=17, minutes=00)
FMT = '%H:%M'





def check_gaps(shifts, start, end):
    last = start
    lastShift = (shifts[0][0], shifts[0][1])
    cases = []
    for shift in shifts:     
        if (shift[0] > lastShift[0] and shift[1] < lastShift[1]):
            continue
         
        diff = shift[0] - last
        if (diff > datetime.timedelta(hours=0, minutes=0)):
            cases.append((last, shift[0]))
        last = shift[1]
        lastShift = (shift[0], shift[1])
    diff = end - last
    if (diff > datetime.timedelta(hours=0, minutes=0)):
        cases.append((last, end))
    return cases


def in_time(case, find):
    #check the time to find is within the case
    if (find[0] >= case[0] and find[1] <= case[1]):
        return True
    return False

for shift in shifts: #each shift
    for day, value in shift.items(): #each day
        for dep in value: #each department
            full = []
            for k, s in dep.items(): #each shift in each department
                for final in s: #each item in each shift
                    start = datetime.timedelta(hours=final[1], minutes=final[2])
                    end = datetime.timedelta(hours=final[3], minutes=final[4])           
                    full.append((start, end))
                             
            for f in sorted(full):
                print(f[0], f[1])
                    
            print("gaps:")
            cases = check_gaps(sorted(full), cStart, cEnd)
            for case in cases:
                print(case[0], case[1])
            print("\n")
            

#complexity O(n^5) <- shit
                    

        
            
            
            
            