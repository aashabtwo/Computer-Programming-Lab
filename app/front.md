# Frontend Doc

## Registration
Basic registration page. Fields -> name, email, position, password, confirm password

## Login
Basic login page. Fields -> email, password

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
For each of these lab, there will be three different urls: theory, practice, lab

### 1) Theory
Consists basic theory around what the lab will take place

### 2) Practice
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
In the page showing the list of practice problems, the front only need to show the "title" and "description"
