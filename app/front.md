# Frontend Doc

## Registration
Basic registration page. Fields -> name, email, position, password, confirm password

## Login
Basic login page. Fields -> email, password

## Lab
Will contain a list of labs the student has joined. An array will be sent and each member of the array will look like: 
```
[
	{
		"lab_name",
		"lab_category", // Programming, Mechatronics etc.
		"department",
		"lab_teachers"
	}
	...
]


```

## C Programming Lab
Will consist of lab heads (lab one, lab two, etc.). Data to be sent from server

```
[
	{
		"head": "lab one",
		"title": "Conditional Statements", // just an example
	}
]

```
For each of these lab, there will be three different urls: theory, practice, lab events

#### 1) Theory
Consists basic theory around what the lab will take place. The data that will be sent will be like:

```
{
	"theory" : "<theory_data>"
}
```
This data is to be displayed.

#### 2) Practice
Will consist of practice problems for students to solve. An array of data will be sent from the server. Each of the array memeber will look like this

```
[
	{
		"title",
		"description",
		"objective",
		"task",
		"input_content",
		"output_content",
		"iter_num",
		"lab_head"
	}
	...
]

```
In the page showing the list of practice problems, the front only need to show the "title" and "description" for each problem. Clicking on each of the problems will lead to a page containing the full problem, where all the information as shown above should be displayed.

#### 3) Lab Events
The lab section will consist of a list of assignments, accepted submissions and rejected submissions. These may or may not be there. If no data for any of these are sent from the server, the assignments page should display an appropriate message

**Assignments:**
The array will be sent from the server, each element of the array will look like this:

```
[
	{
		"title",
		"description",
		"objective",
		"task",
		"input_content",
		"output_content",
		"iter_num",
		"marks",
		"lab_name",
		"lab_id",
		"assigned_by", // name of the teacher who gave the task
	}
	...
]

```
In the list, the front only need to show the "title", "description", "marks" and "assigned_by" fields for each problem.

**Accepted Submissions:**
Is an array. For each element:

```
[
	{
		"code",
		"remarks",
		"accepted" // bool - True
	}
	...
]

```

**Rejected Submissions:**
Same as accepted submissions, except the "accepted" field will be False

## Routes

### Get Routes
1. **/practice/problems** -> Route to get the list of problems for students to practice. The data will be sent as an array, and will look like:

```
[
	// a single element of the array
	{
		"title",
		"description",
		"objective",
		"task",
		"input_content",
		"output_content",
		"iter_num",
		"lab_head"
	},
	...
]

```
Only "title" and "description" should be used to display the problem inside a card for each problem

2. **/practice/problems/{id}** -> Route to get one problem (for practice) corresponding to the id. The problem should be fully described by showing all the elements excepted "iter_num".

```
{
	"title",
	"description",
	"objective",
	"task",
	"input_content",
	"output_content",
	"iter_num",
	"lab_head"
}
```

4. **/lab/problems/{id}** -> Route to get one problem. Data will look like:

```
{
	"title",
	"description",
	"objective",
	"task",
	"input_content",
	"output_content",
	"iter_num",
	"marks"
},
```
5. **/labs-all** -> display all Labs that were created.
```
[	
	// each element
	{
		"lab_name",
		"lab_category",
		"department",
	},
	...
]
```
6. **/labs** (Protected Route for teachers) -> shows a list of labs created by the authenticated teacher.

```
[	
	// each element
	{
		"lab_name",
		"lab_category",
		"department",
	},
	...
]
```

7. **/labs/{id}** -> (Protected Route for teachers) -> shows one of the labs created by the authenticated teacher.
8. **/labs/{id}/problems** -> (Protected Route for teachers) -> shows a list of problems from which the authenticated teacher can select for assignments.
```
[	
	// each elemnent
	{
		"title",
		"description",
		"objective",
		"task",
		"input_content",
		"output_content",
		"iter_num",
		"marks"
	},
	...
]

```

9. **/labs/{id}/problems/{problem_id}** -> (Protected Route for teachers) -> shows one of the problems from which the authenticated teacher can select for assignments.
```
{
	"title",
	"description",
	"objective",
	"task",
	"input_content",
	"output_content",
	"iter_num",
	"marks"
},
```
10. **/labs/{id}/assignmentsubmissions** -> (Protected Route for teachers) -> Shows the list of assignment submissions

```
[	
	// each element
	{
		"title",
		"code", // should be displayed inside <pre> tags
		"submitted_by",
		"accepted", // boolean
		"reviewed", // boolean
	},
	...
]
```
Each submission card should show "title" and "submitted_by".

11. **/labs/{id}/assignmentsubmissions/{submission_id}** -> (Protected Route for teachers) -> shows one of the submissions.
```
{
	"title",
	"code", // should be displayed inside <pre> tags
	"submitted_by",
	"accepted", // boolean
	"reviewed", // boolean
}

```
12. **/lab** (Protected route for students) -> Get the list of labs the student is registered in
```
[	
	// each element
	{
		"lab_name",
		"lab_category",
		"department",
	},
	...
]
```
13. **/lab/{id}** (Protected route for students) -> Get one lab the student is registered in

```
{
	"lab_name",
	"lab_category",
	"department",
}
```
14. **/lab/{id}/assignments** (Protected route for students) -> Get the list of assignments for the lab.

```
[	
	// each element
	{
		"title",
		"description",
		"objective",
		"task",
		"marks",
		"input_content",
		"output_content",
		"iter_num",
		"lab_name",
		"lab_id",
		"assigned_by",
	},
	...
]
```
Each assignment card should show "title", "description", "assigned_by" and "marks".
15. **/lab/{id}/assignment/{assignment_id}** (Protected route for students) -> Get one assignment for the lab (shows all the details about the assignment)

```
{
	"title",
	"description",
	"objective",
	"task",
	"marks",
	"input_content",
	"output_content",
	"iter_num",
	"lab_name",
	"lab_id",
	"assigned_by",
}

```
Not to show -> "lab_id", "input_content", "output_content".
16. **/lab/{id}/results/{bool}** (Protected route for students) -> Get the submission results for students.
```
[	
	// each element
	{
		"title",
		"code", // should be displayed inside <pre> tags
		"submitted_by",
		"accepted", // boolean
		"reviewed", // boolean
	},
	...
]
```

### Post Routes
1. **/login** -> **LOGIN**: post request for loging in. The request body should have:
```
{
	"email",
	"password"
}
```
2. **/practice/problems/{id}** -> request route to submit submission file. The post request should send a file (multipart/form-data).
3. **/createlab** -> **Create Lab** : request route to create a lab.
4. **/lab/join/{id}** -> **Join Lab**: request route for students to join a lab.
5. **/labs/{id}/problems/{problem_id}**: **Give Assignment**: (Protected route for teachers)request to give the problem with this id as assignment.
7. **/labs/{id}/assignmentsubmissions/{submission_id}/runcode** -> **Run Code**: (Protected route for teachers): run submitted code
8. **/lab/{id}/assignment/{assignment_id}** -> **Submit Assignment**: (Protected route for students): Run submitted code

### Put Requests
1.  **/labs/{id}/assignmentsubmissions/{submission_id}/accept** -> Route with which teachers will mark a submissions as accepted
2. **/labs/{id}/assignmentsubmissions/{submission_id}/accept** -> Route with which teachers will mark a submission as rejected


### Delete Requests
1. **/logout** -> **Logout**: logout user
