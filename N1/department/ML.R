library(RMySQL)
library(DBI)


args <- commandArgs(TRUE) # only need to run once for the entire file
# get the first argument passed in from the Rscript shell command (from php)
N <- args[1]
drv <- dbDriver("MySQL")
con <- dbConnect(drv, username="g1117493", password="Group12", dbname ="g1117493", host="mydb.ics.purdue.edu")


##########performance
querystatment <- "select events.student_id,Gpa, AVG((assignments.duration*60)/(UNIX_TIMESTAMP(events.event_end) -  UNIX_TIMESTAMP(events.event_start))) as per from ((events inner join assignments on assignments.assignment_id = events.assignment_id) inner join students on students.id = events.student_id) inner join courses on assignments.course_id = courses.id where departname ="





data <- dbGetQuery(con, paste(querystatment, N, " group by events.student_id"))

dbSendQuery(con,paste("DELETE FROM kmeans WHERE dept_id = ", N))

if(nrow(data) >= 10){
model <- kmeans(data[,c(2,3)], 4)



highest_ach <- which.max(model$centers[,1] + model$centers[,2])

best_std <- data$student_id[model$cluster == highest_ach]

post_table <- data.frame(dept = rep(N, length(best_std)), std_id = best_std)



dbstat <- paste("INSERT INTO kmeans(dept_id, student_id) VALUES ("
                , post_table$dept , ", ", post_table$std_id , ")")

for(i in 1:length(dbstat)){
  
  dbSendQuery(con, dbstat[i])
  
}

}

dbDisconnect(con)


