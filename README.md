# gegasoft-form-builder-class
A Standalone Form Builder Class written in PHP language to generate HTML forms and to validate input by predefined rules or regex patterns. It is designed by Gegasoft. This is really very small in size under 10kb and facilitate a lot in creating HTML forms and validating input fields.

# Usage Examples:
## 1. Creating Forms
Syntax: 
```
Form::create($attr, ...$elements);
```
- $attr (str): attributes without quotes i.e. 'id=create_user, method=get, action=form/action/url, class=form-horizontal'
- $elements: it is the body of the form. You can add as many input fields by generating from Form or strings.
### Example:
```
Form::create('id=create_user, method=get, action=form/action/url, class=form-horizontal')
```
The above will produce the following html code
```
<form id="create_user" method="get" action="form/action/url" class="form-horizontal"></form>
```
## 2. Creating Input Fields
### Supported Input Fields
- label
- text
- submit
- password
- email
- hidden
- option
- select
- file
- radio
- checkbox
- textarea
- date
- number
- datetime
- any XML tag
### Usage:
Using Input Method
```
Usage1: Form::input($type, $name, $value, $attr, ...$elements)
Usage2: Form::$tag_name($name, $value, $attr, ...$elements)
Usage3: Form::{$tag_name}_field($name, $value, $attr, ...$elements)
```
- $type - The type of supported input fields above
- $name - The name of the input field it is required
- $value - The default value of the input fields (ignored in select input because set selected for options in select. as shown bellow in later examples)
- $elements - Add some HTML after input field
- $tag_name - You can use input field as method to generate input field for example, Form::text() will generate textbox input field and Form::radio will generate radio button input field. Also it applies to custom XML elements. Form::div() will create a div element, Form::section() will create a section element.

Examples:
1. Text Input Example
```
Form::input('text','username','Gegasoft','id=username, placeholder=Enter username..., class=col-md-4')
```
The above will generate the following
```
<input type='text' name='username' value='Gegasoft' id="username" placeholder="Enter username..." class="col-md-4" />
```
2. Radio Input Example
```
Form::radio('gender','M','class=col-md-2',' Male')
```
The above is second method of call and it will generate the following html code
```
<input type='radio' name='gender' value='M' class="col-md-2" /> Male
```
3. With Label Example
```
Form::label('email','Email:') . Form::email('email','info@gegasoft.com','class=col-md-2, id=email');
```
We have concateneated two generated codes. So it will produce the following
```
<label for='email' >Email:</label><input type='email' name='email' value='info@gegasoft.com' class="col-md-2" id="email" />
```
4. Nested containers
```
Form::div('class=form-group',
  Form::label('pwd','Password:','class=control-label col-sm-2') .
  Form::div('class=col-sm-10',
    Form::password('password','','class=form-control, id=pwd, placeholder=Enter password')
  )
)
```
We can nest any number of levels to produce required HTML. For exmaple, above will generate as following (i have tidy clean for you let understand):
```
<div class="form-group">
  <label for='pwd' class="control-label col-sm-2">Password:</label>
  <div class="col-sm-10">
    <input type="password" name="password" value='' class="form-control" id="pwd" placeholder="Enter password">
  </div>
</div>
```
5. Usage 3 Example:
```
Form::label_field('age','Your Age:') . Form::number_field('age','10','id=age')
```
The above will generate a numeric input field as following. Notice we used label_field instead of label and number_field instead of number
```
<label for='age' >Your Age:</label><input type='number' name='age' value='10' id="age" />
```

## Remember
- **All Static Method:**  The Form class have all static methods. You don't need to create an object from Form class. 
- **Always Return String:** The Form class always return string. You can save into a variable or print/echo. 
- **Input() Method:** The input method requires an extra attribute. For example, the first attribute is the type of the input element. i.e. Form::input('text', 'username', 'ali') is same as Form::text('username','ali') or Form::text_field('username','ali')

# Validating Input Fields
Usage:
```
Form::validate($rules, $input_fields)
```
- **$rules** key valued paired rules. keys are mapped on the input fileds on which the rules will be applied and values are the rules to be checked.
- **input_fields** key valued paired input fields. For example, you can directly pass $\_POST to check if posted values are according to validation rules.
