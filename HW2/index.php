<?php
/*
 * Agenda:
 * JavaScript error validation
 *      *finish name textbox
 *      *implement checkbox validation
 * Mixing JS and PHP validation
 * Styling into a 3-column layout
 * "Admin" page for serving CSV file
 */

//setup default form values
$form_values = array(
    'name' => ''
);

//used to build checkboxes for class days
$class_days = array(
    'monday' => 'M',
    'tuesday' => 'Tu',
    'wednesday' => 'W',
    'thursday' => 'Th',
	'friday' => 'F',
	'saturday' => 'Sat',
	'sunday' => 'Sun'
);

//TODO: consider how we might cache this array
$class_days_checked = array();
foreach($class_days as $key => $value)
{
    $class_days_checked[$key] = '';
}

//print_r($_REQUEST);

//did we receive form data?
$errors = array();
if(!empty($_REQUEST['submit-form']))
{
    //populate old form values
    foreach($_REQUEST as $key => $value)
    {
        $form_values[$key] = $value;
    }

    //update CB values
    if(!empty($_REQUEST['class-days']))
    {
        foreach($_REQUEST['class-days'] as $value)
        {
            $class_days_checked[$value] = 'checked="checked"';
        }
    }

    //was our name textbox empty?
    if(strlen($_REQUEST['name']) == 0)
    {
        //textbox is blank
        $errors[] = "Please enter your name.";
    }
    else
    {
        //check to make sure that we only have text
        $pattern = '/[a-zA-Z]+/';
        preg_match($pattern, $_REQUEST['name'], $result);

        //first off, do I have any matches?
        if(count($result) > 0)
        {
            //does first item length match input length?
            if(strlen($result[0]) != strlen($_REQUEST['name']))
            {
                $errors[] = 'Names cannot contain numbers or special characters';
            }
        }
        else
        {
            $errors[] = 'Names cannot contain numbers or special characters';
        }
    }

    //validate checkbox data
    //Q1: did we get any CB data?
    if(empty($_REQUEST['class-days']) ||
        count($_REQUEST['class-days']) == 0)
    {
        $errors[] = 'Please select at least one day that you have class';
    }

}

//we might ask: are error free?  If so, save to DB, file, etc.

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
	<title>Simple Form</title>

	<script type="text/javascript" src="validateEmail.js"></script>
    <script type="text/javascript">
   
        var validateNameTextBox = function()
        {
            //tracks whether or not the form is validated
            var allowSubmit = true;

            //TODO: validate
            //validate name box
            //grab the text inside of our name textbox
            var nameTextBox = document.getElementById("name");
            var nameError = document.getElementById("name-error");
            var nameText = nameTextBox.value;
            if(nameText.length === 0)
            {
                allowSubmit = false;
                nameError.style.display = 'block';
            }
            else
            {
                //is this a valid name?
                var result = nameText.match(/[a-zA-Z]+/);
                if(result != undefined && result.length > 0)
                {
                    //was the first match the same length as the original
                    //string?
                    if(result[0] !== result.input)
                    {
                        allowSubmit = false;
                        nameError.style.display = 'block';
                    }
                }
                else
                {
                    allowSubmit = false;
                    nameError.style.display = 'block';
                }
            }

            if(allowSubmit === true)
            {
                nameError.style.display = "none";
            }

            return allowSubmit;
	}

		var initDate = function(){
				var selectMonth = document.getElementById("select-month");
				var selectDay = document.getElementById("select-day");
				var selectYear = document.getElementById("select-year");
				var num, text, option;
				
				num = 1;
				text = "0";	
				while(num <= 12){
						option = document.createElement("option");
						if(num < 10){
							option.text = text + num++;	
						}	
						else{
							option.text = num++;
						}
						selectMonth.add(option);
				}
				
				num = 1;
				text = "0";	
				//september, april, june, and november have 30 days
				while(num <= 31){
						option = document.createElement("option");
						if(num < 10){
							option.text = text + num++;	
						}	
						else{
							option.text = num++;
						}
						selectDay.add(option);
				}

				num = 2016;
				while(num <= 2030){
					option = document.createElement("option");
					option.text = num++;
					selectYear.add(option);
				}

				selectMonth.addEventListener("change", function (){
						var monthThirty = {"04":"september", "09":"april",
							   	"11":"november", "06":"june"};
						var feb = "02";

						if(selectMonth.value in monthThirty){ //if we selected a 30month
							
								num = selectDay.length + 1; //about to add, so add 1 for new day

								while(selectDay.length < 30){ //if it's feb
										option = document.createElement("option");
										option.text = num++;
										selectDay.add(option);
								}

								num = selectDay.length; //no need to add + 1 because we're removing one
								
								while(selectDay.length > 30){ //if 31month
										selectDay.remove(num--);
								}
						
						}
						
						else if(selectMonth.value == feb){ //if we selected february
								var leap = (selectYear.value % 4 == 0 && selectYear.value % 100 != 0) ? 29 : 28;
								num = selectDay.length;	
								
								//if it's not already february, must be greater 
								while(selectDay.length != leap){
										selectDay.remove(num--);
								}
					
						}

						else{ //if we selected 31month
								num = selectDay.length + 1;	
								//if it's not already february, must be greater
								while(selectDay.length != 31){
										option = document.createElement("option");
										option.text = num++;
										selectDay.add(option);
								}
						}
					
				});

				//takes care of leap year case
				selectYear.addEventListener("change", function (){
						var feb = "02";
						if(selectMonth.value == feb){	
								var leap = (selectYear.value % 4 == 0 && selectYear.value % 100 != 0) ? 29 : 28;
								num = selectDay.length + 1;	
									
								while(selectDay.length < leap){ 
										option = document.createElement("option");
										option.text = num++;
										selectDay.add(option);
								}
								
								num = selectDay.length;	

								while(selectDay.length > leap){
										selectDay.remove(num--);
								}
						}

				});
		}
	
		var initMajors = function(){
			var majors = [	
				"Accounting",
				"Agricultural and Food Business Economics",
				"Agricultural and Food Systems",
				"Agricultural Biotechnology",
				"Agricultural Education",
				"Agricultural Technology and Production Management",
				"Agriculture and Food Security",
				"Animal Sciences",
				"Anthropology",
				"Apparel Design, Merchandising, and Textiles",
				"Architectural Studies",
				"Art",
				"Asian Studies",
				"Athletic Training",
				"Basic Medical Sciences",
				"Biochemistry",
				"Bioengineering",
				"Biology",
				"Chemical Engineering",
				"Chemistry",
				"Chinese",
				"Civil Engineering",
				"Communication and Society",
				"Comparative Ethnic Studies",
				"Computer Engineering",
				"Computer Science",
				"Construction Management",
				"Criminal Justice",
				"Digital Technology and Culture",
				"Economic Sciences",
				"Electrical Engineering",
				"Elementary Education",
				"English",
				"Entrepreneurship",
				"Environmental and Ecosystem Sciences",
				"Geology (Earth Sciences)",
				"Field Crop Management",
				"Finance",
				"Fine Arts",
				"Food Science",
				"Foreign Languages",
				"Forestry",
				"French",
				"Fruit and Vegetable Management",
				"Genetics and Cell Biology",
				"Social Sciences: General Studies",
				"History",
				"Hospitality Business Management",
				"Human Development",
				"Humanities: General Studies",
				"Integrated Plant Sciences",
				"Interior Design",
				"International Business",
				"Journalism and Media Production",
				"Landscape Architecture",
				"Landscape, Nursery, and Greenhouse Management",
				"Management",
				"Management Information Systems",
				"Marketing",
				"Materials Science and Engineering",
				"Mathematics",
				"Mechanical Engineering",
				"Microbiology",
				"Music",
				"Music Composition",
				"Music Education",
				"Music Performance",
				"Neuroscience",
				"Nursing (BSN)",
				"Nursing (RN to BSN)",
				"Nutrition and Exercise Physiology",
				"Organic Agriculture Systems",
				"Philosophy",
				"Physics",
				"Political Science",
				"Psychology",
				"Social Sciences: General Studies",
				"Social Studies Teaching",
				"Sociology",
				"Spanish",
				"Speech and Hearing Sciences",
				"Sport Management",
				"Sport Science",
				"Strategic Communication",
				"Turfgrass Management",
				"Winemaking (Viticulture and Enology)",
				"Zoology"
			]
			

			var selectMajor = document.getElementById("select-major");
			var option;

			for(var i = 0; i < majors.length; i++){
				option = document.createElement("option");
				option.text = majors[i];	
				selectMajor.add(option);	
			}

			selectMajor.value = "Computer Science";
	

		}

		var init = function(){
			initDate();
			initMajors();
		}

        document.addEventListener("DOMContentLoaded", function ()
        {
			validateNameTextBox();
			init();

            //validation after we get data
            document.getElementById("name")
                .addEventListener("change", validateNameTextBox);

            //validation on button click
            document.getElementById("submit-form")
                .addEventListener("click", function(evt){

                    var allowSubmit = validateNameTextBox();

                    //did we encounter errors?
                    if(allowSubmit === false)
                    {
                        //stop click event
                        //evt.preventDefault();
                    }

				});

		});
    </script>
	<style type="text/css">
		body {
			font-family: "Trebuchet MS", Helvetica, sans-serf;
			margin: 0px;
			background-color: #D2D4F5;
			padding: 0;
		}
        .error-message
        {
            display:none;
            color:red;
            font-weight:bold;
        }

        .question div
        {
			float:left;	
        }

        .question h1
        {
            width:150px;
            font-size:12px;
        }

        .error-message
        {
            padding-left:25px;
        }

        .question
        {
            clear:both;
		}

		.question a{
			font-size: 40px;
		}

		div.topbar{
			background: rgba(0, 0, 0, 0.86);
			color:#FFFFFF;
			top: 0;
			width: 100%;
			height:50px;
			font-weight: bold;
			margin-bottom: 15px;
			text-align: center;
		}

		div.topbar a{
			line-height:2;
			font-size: 24px;
		}

		#form1{
			padding-top: 50px;
			margin-left:auto;
			margin-right:auto;
			margin-left: auto;
			width: 52em;
			padding-left:60px;
		}

		div.col0{
			padding-bottom: 10px;
			width: 400px;
		}

		div.col1{
			padding-top: 28px;
			padding-left:25px;
		}

		div.col0 h1{
			width: 100%;
		}

		#ul-days li{
			margin-left:-35px;
			padding-right: 40px;
			clear: both;
			display: inline;
			
		}

		#ul-days li input{
				
		}

		#ul-days li label{
			margin-left:-7px;	
		}
	
    </style>
</head>
<body>
	<div class="topbar"><a>Cougar Club Survey</a></div>
	
    <article id="error-messages">
        <?php if(count($errors) > 0): ?>
            <h1>Errors were encountered in your submission</h1>
            <ul>
                <?php foreach($errors as $error): ?>
                    <li><?php print $error; ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </article>
    <!--Submit form to ourselves using HTTP POST-->
    <form id="form1" method="post" action="<?php print $_SERVER['PHP_SELF']; ?>">
        <article class="question">
            <div class="col0"><h1><label for="name"><a>Name:</a></label></h1></div>
            <div class="col1" id="name-input"><input id="name" name="name" type="text" size="20"
                        value="<?php print $form_values['name']; ?>"
                        required="required"
                /> </div>
            <div id="name-error" class="error-message col1"
                 style="<?php
                 /*
                  * IF name text box was invalid:
                  *    print('display:block;')
                  */
                 ?>"

            >
                Please enter a valid name.
            </div>
        </article>
        <article class="question">
            <div class="col0"><h1><label for="name"><a>Class Standing:</a></label></h1></div>

			<div class="col1">
				
				<div>
						<input id="cs-freshman" name="class-standing" type="radio"
								value="freshman" size="20" />
						<label for="cs-freshman">Freshman</label>
				</div>

				<div>
						<input id="cs-sophomore" name="class-standing" type="radio"
								value="sophomore" size="20" />

						<label for="cs-sophomore">Sophomore</label> </div>	<div style="clear: both; padding-top:4px;">
						<input id="cs-junior" name="class-standing" type="radio"
								value="junior" size="20" />

						<label for="cs-">Junior</label>
				</div>
				
				<div style="padding-top:4px; padding-left:28px;">
						<input id="cs-senior" name="class-standing" type="radio"
								value="senior" size="20" />

						<label for="cs-senior">Senior</label>
				</div>
				
				<div style="padding-top:4px; padding-left:34px;">
						<input id="cs-other" name="class-standing" type="radio"
								value="other" size="20" />

						<label for="cs-other">Other</label>
				</div>
            </div>
			
				
		</article>

		<article class="question">
			<div class="col0"><h1><a>Exp. Graduation:</a></h1></div>
			
			<div class="col1">
				<div>
					<select id="select-month">
					</select>
				</div>
				
				<div style="padding-left: 5px;">
					<select id="select-day">
					</select>
				</div>
				
				<div style="padding-left: 5px;">
					<select id="select-year">
					</select>
				</div>
			</div>
		</article>
		
		<article class="question">
			<div class="col0"><h1><a>Major:</a></h1></div>
			<div class="col1">
				<select id="select-major"> </select>		
			</div>		
			
		</article>
		
		<article class="question">
			<div class="col0"><h1><a>Email:</a></h1></div>
			<div class="col1">
				<input id="input-email" size="30" type="email" required="required"></input>
			</div>		
			
		</article>

		<article class="question">
			<div class="col0"><h1><a>Hobbies:</a></h1></div>
			<div class="col1">
				<textarea rows="4" cols="50"  id="textarea-hobbies"></textarea>
			</div>		
			
		</article>

        <article class="question">
			<div class="col0"><h1><a>Gender:</a></h1></div>
			
			<div class="col1">
					<div>
						<input id="gender-male" name="gender" type="radio"
								value="male" size="20" />
						<label for="gender-male">Male</label>
					</div>
					<div style="padding-left:12px;">
						<input id="gender-female" name="gender" type="radio"
							   value="female" size="20" />
						<label for="gender-female">Female</label>
					</div>
			</div>

            <div id="gender-error" class="error-message"><!--TODO--></div>
        </article>
        <article class="question">
            <div class="col0"><h1><a>Availability:</a></h1></div>
            <div class="col1">
                <ul id="ul-days">
                    <?php foreach($class_days as $lower => $upper): ?>
                        <li>
                            <input
                                type="checkbox"
                                name="class-days[]"
                                id="days-<?php print $lower; ?>"
                                value="<?php print $lower; ?>"
                                <?php print $class_days_checked[$lower]; ?>
                            />
                            <label for="days-<?php print $lower; ?>">
                                <?php print $upper; ?>
                            </label>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="days-error" class="error-message"><!--TODO--></div>
		</article>

        <article class="question">
			<div class="col0"><h1><a>Volunteer Activities:</a></h1></div>
			
			<div class="col1">
					<select id="select-activity">
						<option default>-- Select Activity --</option>
					 </select>	
			</div>
		</article>

        <article class="question">
			<div class="col0"><h1><a>Role:</a></h1></div>
			
			<div class="col1">
				<div>
						<input id="role-team-leader" name="role" type="radio"
								value="team-leader" size="20" />
						<label for="role-team-leader">Team-Leader</label>
				</div>

				<div style="padding-left:12px;">
						<input id="role-volunteer" name="role" type="radio"
								value="volunteer" size="20" />
						<label for="Volunteer">Volunteer</label>
				</div>
			</div>
		</article>

		<div class="col0">
				<button type="submit" name="submit-form" id="submit-form" value="submit">Submit</button>
		</div>
    </form>
</body>
</html>
