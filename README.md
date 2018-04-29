# Student Studies

## Team Members 
* Riley Hedgpath
* Daniel Jaegers 
* Jamie Flores 
 
## Project Description
For our project we created a program that allows students to find study partners. 

## Database Schema 

### requests 
| Field | Type | Null | Default | Key | Auto Increment |
|:----:|:------:|:------:|:------:|:------:|:-------------:|
| id | int(11) | No | None | Primary | Yes |
|name | varchar(40) | No | None | None | No |
| pawprint | varchar(40) | No | None | None | No |
| description | mediumtext | Yes | NULL | None | No |
| dateCreated | datetime | No | NOW() | None | No |
| dateCompleted | datetime | No | NOW() | None | No |
| course_id | int(11) | No | None | None | No |

### courses
| Field | Type | Null | Default | Key | Auto Increment |
|:----:|:------:|:------:|:------:|:------:|:-------------:|
| id | int(11) | No | None | Primary | Yes |
| code | varchar(40) | No | None | None | No |
| title | varchar(40) | No | None | None | No |
| description | mediumtext | Yes | NULL | None | No |

## Entity Relationship Diagram
![alt text](https://github.com/jamiefloress/studentstudies/blob/master/studentStudiesERD.png "ERD")

## Create, Read, Update, Delete
* Create: When users enter in a new request, they are creating a request that goes into our database. 
* Read: When the display of the information that has been inserted into our tables is shown.
* Update: Users can enter in their student numbers to update the forms they filled our for their request. 
* Delete: Once users have found a match, they can verify their student number and remove their request. 

## Video


