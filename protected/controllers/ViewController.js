
function CloseAll(){																//function that set all changeable divs in "visibility:hidden;"!
	$("div.index_div_register").css('visibility','hidden');
    $("div.index_div_login").css('visibility','hidden');
    $("div.index_div_ingredient").css('visibility','hidden');
    $("#errorRegisterFirstname").css('visibility','hidden');
    $("#errorRegisterLastname").css('visibility','hidden');
    $("#errorRegisterUsername").css('visibility','hidden');
    $("#errorRegisterEmail").css('visibility','hidden');
    $("#errorRegisterPass").css('visibility','hidden');
    $("#errorRegisterPassT").css('visibility','hidden');
    $("#errorRegisterfields").css('visibility','hidden');
    ("div.index_div_lang").css('visibility','hidden');
}

function ShowView(view){															//function that shows the actual wished view!

	/*
	 * Views:
	 * 0.			INDEX
	 * 1.			LOGIN
	 * 2.			SETTINGS
	 * 3.			LANGUAGE
	 * 4.			PLAN YOUR MEAL NOW
	 * 5.			SEARCH RECIPE
	 * 6.			SEARCH FOOD
	 * 7.			THE COOKING MACHINE
	 * 8.			REGISTER
	 * x.			CREATE INGREDIENT
	 */

	if (view == "0"){																//View 0 == Index!
		CloseAll();
	}

	if (view == "1"){																//View 1 == Login!
		if ($("div.index_div_register").css('visibility')=="visible") {				//if the register div is open, all divs gettin hidden!
			CloseAll();
	       }
	    else {
	        if ($("div.index_div_login").css('visibility')=="visible"){				//if login is visible: all divs gettin closed!
	        	CloseAll();
	        }
	        else {
	            $("div.index_div_login").css('visibility','visible');				//if register and login hidde, set's login to show!
	        }
	    }
	}

	if (view == "2"){																//View 2 == Settings!

	}
	
	if (view == "3"){																//View 3 == Language!
		if ($("div.index_div_lang").css('visibility')=="visible"){					//if language == visible : set to invisible!
		    $("div.index_div_lang").css('visibility','hidden');
		}
		else {
	        $("div.index_div_lang").css('visibility','visible');					//if language == invisible : set to visible!				
		}

	}

	if (view == "4"){																//View 4 == Plan your meal now!
		alert("platzhalter plan ur meal now^^");
	}
	
	if (view == "5"){																//View 5 == Search recipe!
		alert ("platzhalter Search Recipe");
	}
	
	if (view == "6"){																//View 6 == Search food!
		alert("platzhalter Search Food");
	}

	if (view == "7"){																//View 7 == The cooking machine!
		alert("platzhalter The Cooking Machine");
	}

	if (view == "8"){																//View 8 == Register!
		$("div.index_div_login").css('visibility','hidden');
	    $("div.index_div_register").css('visibility','visible');
	    $('#sBRegister').attr('onclick', "");
	    sregister();
	}
	
	if (view == "x"){																//View x (undefined) == CreateIngredient!
	    if ($("div.index_div_ingredient").css('visibility')=="visible") {
	        $("div.index_div_ingredient").css('visibility','hidden');
	    }
	    else {
	        $("div.index_div_ingredient").css('visibility','visible');
	    }
	}
}