# Tell R that you are passing in arguments from an external source
library(RMySQL)
library(DBI)
args <- commandArgs(TRUE) # only need to run once for the entire file
# get the first argument passed in from the Rscript shell command (from php)
N <- args[1]

# generate N random numbers (normally distributed) to plot
# IMPORTANT: you DO NOT have permission to install packages on the server,
# talk to Mario if a specific package is required that is NOT already installed
# To get a listing of all the R packages installed on the Purdue SERVER
# installed.packages()
# Tell R that you are passing in arguments from an external source

drv <- dbDriver("MySQL")
con <- dbConnect(drv, username="g1117493", password="Group12", dbname ="g1117493", host="mydb.ics.purdue.edu")
createtime <- function(timeinsecond){
  as.POSIXct(timeinsecond,origin = "1970-01-01",tz = Sys.timezone())
  
}

createdate <- function(date){
  as.numeric(as.POSIXct(date,origin = "1970-01-01",tz = Sys.timezone()))
  
}


# querystatment <- "select assignment_id,startdate,enddate,duration, assignments.type as type from ((students inner join roster on students.id = roster.student_id) inner join courses on courses.id = class_id) inner join assignments on courses.id = assignments.course_id WHERE student_id ="


querystatment <- "select assignment_id,startdate,enddate,duration, assignments.type as type from ((students inner join roster on students.id = roster.student_id) inner join courses on courses.id = class_id) inner join assignments on courses.id = assignments.course_id WHERE assignment_id NOT IN (SELECT  assignment_id from events where status = 1 and student_id = "



querystatment <- paste(querystatment, N, ") and student_id = ", N ," and enddate > '", Sys.time(), "' ORDER BY TYPE")

assigments <- dbGetQuery(con, querystatment)

assigments$duration <- assigments$duration * 60
assigments$startdate <- createdate(assigments$startdate)
assigments$enddate <- createdate(assigments$enddate)


querystatment <- "SELECT * FROM students WHERE id = "


querystatment <- paste(querystatment, N)

sleep <- dbGetQuery(con, querystatment)


best_option <- function(demand_temp,dur_temp){
  best <- demand_temp[1:(dur_temp)]
  interval <- 1:(dur_temp)
  
  for(i in 1:(length(demand_temp) - dur_temp + 1)){
    if(sum(best) > sum(demand_temp[i:(i+dur_temp -1)])){
      best <- demand_temp[i:(i + dur_temp -1)]
      interval <- i:(i +dur_temp -1)
    }
    
  }
  return(interval)
  
}





# random generation - delete
# assigments <- data.frame(assignment_id = 1:10,
#                               startdate = startdate,
#                               enddate = floor(startdate + rnorm(10, 2*86400, 86400/4)) ,
#                               duration = floor(rnorm(10, 3600, 100)),
#                               priority = sample(c(1,2,3), 10, replace = TRUE))

# random generation - delete ^




# startdate = floor(runif(10, Sys.time() + 86400,Sys.time() + 2* 86400 ))
# assigments <- data.frame(assignment_id = 1:10, 
#                          startdate = startdate, 
#                          enddate = floor(startdate + rnorm(10, 2*86400, 86400/4)) , 
#                          duration = floor(rnorm(10, 3600, 100)), 
#                          priority = sample(c(1,2,3), 10, replace = TRUE))


# min <- floor((min(assigments$startdate)/1800)) * 1800
min <- max(ceiling(createdate(Sys.time())/1800) * 1800, (floor(min(assigments$startdate)/1800) * 1800))
# max <- floor((max(assigments$enddate)/1800) + 1) * 1800

max <- ceiling(max(assigments$enddate)/1800) * 1800

intervals <- floor(((max- min) / 1800))      #number of available intervals in the calendar 
start_interval <- ceiling(((assigments$startdate - min)/1800))
start_interval [start_interval < 1] <- 1
end_interval <- floor(((assigments$enddate - min)/1800))
end_interval [end_interval < 1] <- 1
duration_interval <- ceiling((assigments$duration/1800))

demand <- rep(0,intervals)    #number of assignments that can possibly be placed in intervals 

final_start_times <- c()
final_end_times <- c()
final_id <- c()
final_type <- c()


############sleep
sleep_time <- (sleep$sleeptime + 4) * 60 * 60 
sleep_duration<- sleep$sleepduration * 60 *60 
days <- ceiling((max -min)/(24* 60 * 60))
start_sleep <- (floor(min/(24*60 * 60)) * (24*60 * 60))+ (24*60*60* 0:(days-1) + sleep_time )
# start_sleep <- start_sleep[start_sleep >= min & start_sleep <= max ]
end_sleep <- start_sleep + sleep_duration
# start_sleep[1] <- max(min,start_sleep[1])
# end_sleep[length(end_sleep)] <- min(max,end_sleep[length(end_sleep)])
start_sleep_interval <- floor(((start_sleep - min)/1800))
start_sleep_interval[start_sleep_interval <= 0] <- 1

end_sleep_interval <- ceiling(((end_sleep- min)/1800))
end_sleep_interval[end_sleep_interval >= intervals] <- intervals
end_sleep_interval[end_sleep_interval <= 0] <- 1
############endsleep

for(l in 1:length(start_interval)){
  demand[(start_interval[l]):end_interval[l]] = demand[start_interval[l]:end_interval[l]] + 1
}

max_data <- sum(demand)

for(l in 1:length(start_sleep_interval)){
  demand[(start_sleep_interval[l]):end_sleep_interval[l]] <- max_data
}

for(i in 1:length(start_interval)){
  best_interval <- best_option(demand[start_interval[i]:end_interval[i]], duration_interval[i])
  best_interval <- best_interval + start_interval[i] - 1
  
  #which(demand[best_interval] < (length(start_interval) + 1))
  # final_intervals <- best_interval[demand[best_interval] < max_data]

  
  ####delete in emergency
  if(duration_interval[i] > 1){
  for(l in 1:(duration_interval[i]-1) & any(demand[best_interval] == max_data)){
    best_interval <- best_option(demand[start_interval[i]:end_interval[i]], duration_interval[i] - l)
    best_interval <- best_interval + start_interval[i] - 1

  }
  }
  #####delete in emergency
  

  final_intervals <- best_interval[demand[best_interval] < max_data]
    
  
  # final_intervals <- best_interval[demand[best_interval] < max_data]
  if(length(final_intervals) == length(best_interval)){
    
    final_start_times <- append(final_start_times, ((min(final_intervals) -1) * 1800 + min))
    final_end_times <- append(final_end_times, ((max(final_intervals) * 1800) + min))
    final_id <- append(final_id,assigments$assignment_id[i] )
    final_type <- append(final_type,assigments$type[i])
    demand[best_interval] <- max_data
    
    
  } 
  
  
}
event = data.frame(id = rep(N, length(final_id)), name = final_id, event_start= createtime(final_start_times), event_end = createtime(final_end_times), type = final_type)


# inster_stm <- function(insert_st, data){
#   data$event_start <- paste("'",data$event_start, "'")
#   data$event_end <- paste("'",data$event_end, "'", sep ="")
#   finalstm <- ""
#   
#   for(i in 1:nrow(data)){
#     finalstm <- paste(finalstm,"(",(paste(data[i,], collapse = ",")), "),")
#   }
#   
#   return(paste(insert_st,substr(finalstm,1,(nchar(finalstm)-1))))
# }


dbSendQuery(con,paste("DELETE FROM events WHERE student_id = ", N, "and status = 0"))

eed <- paste("'",event$event_start, "'")
ssd <- paste("'",event$event_end, "'", sep ="")

colors <- c(Exam = "DC143C", Project = "0000ff", Lab = "00ff00", Homework = "000000")
event$type <- colors[event$type]

eventtype <- paste("'",event$type, "'", sep ="")



# if(nrow(event) < nrow(assigments)){
#   dbstat <- paste("INSERT INTO Notifications(Student_id, Description, Time) VALUES ("
#                   , N , ", 'Some assigments were not assigned due to heavyload' ", Sys.time() ,")")
# 
#   for(i in 1:length(dbstat)){
# 
#     dbSendQuery(con, dbstat[i])
# 
#   }
# 
# }



dbstat <- paste("INSERT INTO events(student_id, assignment_id, event_start, event_end, type) VALUES ("
                , event$id , ", ", event$name , ", ", eed , ", ", ssd ,", ", eventtype, ")")

for(i in 1:length(dbstat)){

 dbSendQuery(con, dbstat[i])
  
}


dbDisconnect(con)

#########

# x <- rnorm(N,0,1)
# # save the plot as a png file in the pics directory (be sure that it exists first!)
# # type="cairo" is necessary or you will have empty plots on output!
# 
# 
# 
# png(filename="temp10.png", width=500, height=500, type="cairo")
# hist(x, col="lightblue")
# dev.off()







