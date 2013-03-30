<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

class CaptchaAction extends CCaptchaAction
{
   protected function generateVerifyCode()
   {
      return mt_rand((int)$this->minLength,(int)$this->maxLength);
   }

   public function renderImage($code)
   {
      parent::renderImage($this->getText($code));
   }

   protected function getText($code)
   {
      $code = (int)$code;
      $rand = mt_rand(1, $code-1);
      $op = mt_rand(0, 1);
      if($op)
         return $code-$rand.»+».$rand;
      else
         return $code+$rand.»-».$rand;
   }
}
