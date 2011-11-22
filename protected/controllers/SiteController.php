<?php

/**
 * SiteController is the default controller to handle user requests.
 */
class SiteController extends CController
{
    /**
     * Index action is the default action in a controller.
     */
    public function actionIndex()
    {
        ?>
        <!DOCTYPE html>
        <html>
            <head>
                <title>EveryCook "The worlds best recipe database"</title>
                <link rel="stylesheet" type="text/css" href="protected/css/styles.css"/>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <script type="text/javascript" src="lib/jquery/jquery-1.6.4.js"></script>
                <script type="text/javascript" src="protected/controllers/ViewController.js"></script>
                <script type="text/javascript" src="protected/controllers/LoginController.js"></script>
                <script type="text/javascript" src="protected/controllers/RegisterController.js"></script>
                <script type="text/javascript" src="protected/controllers/IngredientController.js"></script>
                <script type="text/javascript" src="protected/index.js"></script>
                <?php
                    include 'protected/db/db.php';
                ?>
            </head>
            <body>
                <img src="protected/pics/bg.png" alt="Background" id="index_pic_bg">
                <div id="index_div_content">
                    <a href="#" OnClick='ShowView("0")'>
                        <div>
                            <div class="index_text_middle">
                                <div>
                                    <img id="index_div_logo" src="protected/pics/logo.png" alt="EveryCook Logo">
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" OnClick='ShowView("1")'>
                        <div id="index_div_login">
                            <div class="index_text_middle">
                                <div>
                                    <span id="login"></span>
                                </div>
                            </div>                    
                        </div>
                    </a>
                    <div class="index_div_login">
                        <form name="form_login">
                            <table>
                                <tr>
                                	<td></td>
                                    <td><a href="#" OnClick="ShowView(8)"><div><span id="loginRegister"></span></div></a></td>
                                </tr>
                                <tr>
                                    <td><label for="user"><span id="loginUser"></span></label></td>
                                    <td><input type="text" id="user" name="user"></td>
                                </tr>
                                <tr>
                                    <td><label for="pass"><span id="loginPass"></span></label></td>
                                    <td><input type="password" id="pass" name="pass"></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><input type="button" id="loginButton" OnClick="Login() value=""></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                    <div class="index_div_register">
                        <form name="form_register">
                            <table>
                            	<tr>
                                    <td><label for="firstname"><span id="registerFirstname"></span></label></td>
                                    <td><input onkeyup="sregister()" type="text" id="firstname" name="fname" value="Ihr Vorname"></td>
                                    <td><span class="error" id="errorRegisterFirstname"></span></td>
                                </tr>
                                <tr>
                                    <td><label for="lastname"><span id="registerLastname"></span></label></td>
                                    <td><input onkeyup="sregister()" type="text" id="lastname" name="lname" value="Ihr Nachname"></td>
                                    <td><span class="error" id="errorRegisterLastname"></span></td>
                                </tr>
                                <tr>   
                                    <td><label for="username"><span id="registerUsername"></span></label></td>
                                    <td><input onkeyup="sregister()" type="text" id="username" name="uname" value="Gewünschter Benutzername"></td>
                                    <td><span class="error" id="errorRegisterUsername"></span>
                                    <span class="error" id="errorRegisterUserExist"></span></td>
                                </tr>
                                <tr>    
                                    <td><label for="e-mail"><span id="registerEmail"></span></label></td>
                                    <td><input onkeyup="sregister()" type="text" id="e-mail" name="email" value="Ihre E-Mail Adresse"></td>
                                    <td><span class="error" id="errorRegisterEmail"></span>
                                    <span class="error" id="errorRegisterMailExist"></span></td>
                                </tr>
                                <tr>    
                                    <td><label for="password1"><span id="registerPass"></span></label></td>
                                    <td><input onkeyup="sregister()" type="password" id="password1" name="pass1"></td>
                                    <td><span class="error" id="errorRegisterPass"></span></td>
                                </tr>
                                <tr>    
                                    <td><label for="password2"><span id="registerPassT"></span></label></td>
                                    <td><input onkeyup="sregister()" type="password" id="password2" name="pass2"></td>
                                    <td><span class="error" id="errorRegisterPassT"></span></td>
                                </tr>
                                <tr>
                                	<td></td>
                                	<td><input id="sBRegister" type="button" OnClick="" value=""></td>
                                	<td><span class="error" id="errorRegisterfields"></span></td>
                                </tr>
                            </table>
                        </form>
                    </div>   
                    <a href="#" OnClick="ShowSettings()">
                        <div id="index_div_settings">
                            <div class="index_text_middle">
                                <div>
                                    <span id="settings"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <div id="index_div_mf">
                        <span id="mf"></span>
                    </div>
                    <div id="index_div_mf_t">
                        <span id="mf_t"></span>
                    </div>
                    <a href="#" OnClick="#" id="sr_onclick">
                        <div id="index_div_sr">
                            <div class="index_text_middle">
                                <div>
                                    <span id="sr_content"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" OnClick="#" id="sf_onclick">
                        <div id="index_div_sf">
                            <div class="index_text_middle">
                                <div>
                                    <span id="sf_content"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" OnClick="#" id="tcm_onclick">
                        <div id="index_div_tcm">
                            <div class="index_text_middle">
                                <div>
                                    <span id="tcm_content"></span>
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" OnClick='ShowView("3")'>
                    	<div id="index_div_lang">
                            <div class="index_text_middle">
                                <div>
                                    <span id="lang"></span>
                                </div>
                            </div>
                    	</div>
                    </a>
                    <div class="index_div_lang">
                            <?php
                                langlist();
                            ?>
                        </div>
                    <div class="index_div_ingredient">
                        <form name="form_ingredent">
                        	<table>
                        		<tr>
                                    <td><label for="nut_id">Nut_ID:</label></td>
                                    <td><input type="text" id="nut_id" name="nut_id"></td>
                                </tr>
                                <tr>
                                    <td><label for="ing_pic">Bild auswählen:</label></td>
                                    <td><input type="file" id="ing_pic" name="ing_pic" size="20"></td>
                                </tr>
                                <tr>    
                                    <td><label for="pic_auth">Bild-Author:</label></td>
                                    <td><input type="text" id="pic_auth" name="pic_auth"></td>
                                </tr>
                                <tr>    
                                    <td><label for="ing_tit_EN">Inggredient Title EN:</label></td>
                                    <td><input type="text" id="ing_tit_EN" name="ing_tit_EN"></td>
                                </tr>
                                <tr>   
                                    <td><label for="ing_tit_DE">Inggredient Title DE:</label></td>
                                    <td><input type="text" id="ing_tit_DE" name="ing_tit_DE"></td>
                                </tr>
                                <tr>
                                	<td></td>
                                	<td><input id="sBIngredient" type="button" OnClick="singredent()" value="sent"></td>
                                </tr>   
                            </table>
                        </form>
                    </div>
                </div>
            </body>
        </html>  

        <?php
    }
}