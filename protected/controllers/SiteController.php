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
                <script type="text/javascript" src="protected/index.js"></script>
                <script type="text/javascript" src="protected/profile/langpick.js"></script>
                <?php
                    include 'protected/db/db.php';
                ?>
            </head>
            <body>
                <img src="protected/pics/bg.png" alt="Background" id="index_pic_bg">
                <div id="index_div_content">
                    <a href="#" OnClick="ingredient()">
                        <div>
                            <div class="index_text_middle">
                                <div>
                                    <img id="index_div_logo" src="protected/pics/logo.png" alt="EveryCook Logo">
                                </div>
                            </div>
                        </div>
                    </a>
                    <a href="#" OnClick="ShowLogin()">
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
                            <a href="#" OnClick="register()">
                                <div>
                                    <span id="loginRegister"></span>
                                </div>
                            </a>
                            <label for="user"><span id="loginUser"></span></label>
                            <input type="text" id="user" name="user"><br>
                            <label for="pass"><span id="loginPass"></span></label>
                            <input type="password" id="pass" name="pass"><br><br>
                            <a href="#" id="loginButton" OnClick="Login()">
                                <div>
                                    <span id="loginSent"></span>
                                </div>
                            </a>
                        </form>
                    </div>
                    <div class="index_div_register">
                        <form name="form_register">
                        <label for="firstname"><span id="registerFirstname"></span></label>
                        <input onkeyup="sregister()" type="text" id="firstname" name="fname" value="Ihr Vorname"><br>
                        <span class="error" id="errorRegisterFirstname"></span><br>
                        <label for="lastname"><span id="registerLastname"></span></label>
                        <input onkeyup="sregister()" type="text" id="lastname" name="lname" value="Ihr Nachname"><br>
                        <span class="error" id="errorRegisterLastname"></span><br>
                        <label for="username"><span id="registerUsername"></span></label>
                        <input onkeyup="sregister()" type="text" id="username" name="uname" value="Gewünschter Benutzername"><br>
                        <span class="error" id="errorRegisterUsername"></span><br>
                        <label for="e-mail"><span id="registerEmail"></span></label>
                        <input onkeyup="sregister()" type="text" id="e-mail" name="email" value="Ihre E-Mail Adresse"><br>
                        <span class="error" id="errorRegisterEmail"></span><br>
                        <label for="password1"><span id="registerPass"></span></label>
                        <input onkeyup="sregister()" type="password" id="password1" name="pass1"><br>
                        <span class="error" id="errorRegisterPass"></span><br>
                        <label for="password2"><span id="registerPassT"></span></label>
                        <input onkeyup="sregister()" type="password" id="password2" name="pass2"><br>
                        <span class="error" id="errorRegisterPassT"></span><br>
                        </form>
                        <a href="#" id="sBRegister" OnClick="">
                            <span class="error" id="errorRegisterfields"></span><br>
                            <div>
                                <span id="registerSent"></span>
                            </div>
                        </a>
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
                    <div id="index_div_lang">
                        <div class="index_text_middle">
                            <div>
                                <span id="lang"></span>
                            </div>
                        </div>
                        <div class="index_div_lang">
                            <?php
                                langlist();
                            ?>
                        </div>
                    </div>
                    <div class="index_div_ingredient">
                        <form name="form_ingredent">
                            <label for="nut_id">Nut_ID:</label>
                            <input type="text" id="nut_id" name="nut_id"><br>
                            <label for="ing_pic">Bild auswählen:</label>
                            <input type="file" id="ing_pic" name="ing_pic" size="20"><br>
                            <label for="pic_auth">Bild-Author:</label>
                            <input type="text" id="pic_auth" name="pic_auth"><br>
                            <label for="ing_tit_EN">Inggredient Title EN:</label>
                            <input type="text" id="ing_tit_EN" name="ing_tit_EN"><br>
                            <label for="ing_tit_DE">Inggredient Title DE:</label>
                            <input type="text" id="ing_tit_DE" name="ing_tit_DE"><br>
                        </form>
                        <a href="#" OnClick="singredent()">
                            <div>
                                Sent
                            </div>
                        </a>
                    </div>
                </div>
            </body>
        </html>  

        <?php
    }
}