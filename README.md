# Student Studies

## Team Members 
* Riley Hedgpath
* Daniel Jaegers 
* Jamie Flores 
 
## Project Description
For our project we created a program that allows students to find study partners!
<br/>
**Website:** http://danieljaegers.epizy.com/studentstudies

## Database Schema 

### requests 
| Field | Type | Null | Default | Key | Auto Increment |
|:----:|:------:|:------:|:------:|:------:|:-------------:|
| id | int(11) | No | None | Primary | Yes |
|name | varchar(40) | No | None | None | No |
| pawprint | varchar(40) | No | None | None | No |
| description | mediumtext | Yes | NULL | None | No |
| dateCreated | datetime | No | NOW() | None | No |
| dateCompleted | datetime | Yes | NULL | None | No |
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
* Create: When users enter in a new request, they are creating a request that goes into our database. This button to open the request form is available on the home page. 

* Read: On the homepage, all the requests that have been inserted into our database are displayed. 

* Update: Thier is a button on the homepage next to each users request that allows them to update their request form. 

* Delete: Once users have found a match, they remove their request or mark it as complete. 

## Video
<iframe width="560" height="315" src="https://www.youtube.com/embed/5Ll5q8m630M" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>


<a href="https://youtu.be/5Ll5q8m630M
" target="_blank"><img src="https://youtu.be/5Ll5q8m630M.jpg" 
alt="IMAGE ALT TEXT HERE" width="240" height="180" border="10" /></a>
