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
Consists basic theory around what the lab will take place

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

**Assignments**
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

**Accepted Submissions**
Is an array. For each element:

```
[
	{
		"code",
		"remarks",
		"accepted" // bool - False
	}
	...
]

```

**Rejected Submissions**
Same as accepted submissions, except the "accepted" field will be 'True'