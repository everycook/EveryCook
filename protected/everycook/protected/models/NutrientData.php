<?php

/**
 * This is the model class for table "nutrient_data".
 *
 * The followings are the available columns in table 'nutrient_data':
 * @property string $NUT_ID
 * @property string $NUT_DESC
 * @property double $NUT_WATER
 * @property double $NUT_ENERG
 * @property double $NUT_PROT
 * @property double $NUT_LIPID
 * @property double $NUT_ASH
 * @property double $NUT_CARB
 * @property double $NUT_FIBER
 * @property double $NUT_SUGAR
 * @property double $NUT_CALC
 * @property double $NUT_IRON
 * @property double $NUT_MAGN
 * @property double $NUT_PHOS
 * @property double $NUT_POTAS
 * @property double $NUT_SODIUM
 * @property double $NUT_ZINC
 * @property double $NUT_COPP
 * @property double $NUT_MANG
 * @property double $NUT_SELEN
 * @property double $NUT_VIT_C
 * @property double $NUT_THIAM
 * @property double $NUT_RIBOF
 * @property double $NUT_NIAC
 * @property double $NUT_PANTO
 * @property double $NUT_VIT_B6
 * @property double $NUT_FOLAT_TOT
 * @property double $NUT_FOLIC
 * @property double $NUT_FOLATE_FD
 * @property double $NUT_FOLATE_DFE
 * @property double $NUT_CHOLINE
 * @property double $NUT_VIT_B12
 * @property double $NUT_VIT_A_IU
 * @property double $NUT_VIT_A_RAE
 * @property double $NUT_RETINOL
 * @property double $NUT_ALPHA_CAROT
 * @property double $NUT_BETA_CAROT
 * @property double $NUT_BETA_CRYPT
 * @property double $NUT_LYCOP
 * @property double $NUT_LUT_ZEA
 * @property double $NUT_VIT_E
 * @property double $NUT_VIT_D
 * @property double $NUT_VIT_D_IU
 * @property double $NUT_VIT_K
 * @property double $NUT_FA_SAT
 * @property double $NUT_FA_MONO
 * @property double $NUT_FA_POLY
 * @property double $NUT_CHOLEST
 * @property double $NUT_REFUSE
 */
class NutrientData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return NutrientData the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nutrient_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('NUT_ID, NUT_DESC', 'required'),
			array('NUT_WATER, NUT_ENERG, NUT_PROT, NUT_LIPID, NUT_ASH, NUT_CARB, NUT_FIBER, NUT_SUGAR, NUT_CALC, NUT_IRON, NUT_MAGN, NUT_PHOS, NUT_POTAS, NUT_SODIUM, NUT_ZINC, NUT_COPP, NUT_MANG, NUT_SELEN, NUT_VIT_C, NUT_THIAM, NUT_RIBOF, NUT_NIAC, NUT_PANTO, NUT_VIT_B6, NUT_FOLAT_TOT, NUT_FOLIC, NUT_FOLATE_FD, NUT_FOLATE_DFE, NUT_CHOLINE, NUT_VIT_B12, NUT_VIT_A_IU, NUT_VIT_A_RAE, NUT_RETINOL, NUT_ALPHA_CAROT, NUT_BETA_CAROT, NUT_BETA_CRYPT, NUT_LYCOP, NUT_LUT_ZEA, NUT_VIT_E, NUT_VIT_D, NUT_VIT_D_IU, NUT_VIT_K, NUT_FA_SAT, NUT_FA_MONO, NUT_FA_POLY, NUT_CHOLEST, NUT_REFUSE', 'numerical'),
			array('NUT_ID', 'length', 'max'=>20),
			array('NUT_DESC', 'length', 'max'=>200),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('NUT_ID, NUT_DESC, NUT_WATER, NUT_ENERG, NUT_PROT, NUT_LIPID, NUT_ASH, NUT_CARB, NUT_FIBER, NUT_SUGAR, NUT_CALC, NUT_IRON, NUT_MAGN, NUT_PHOS, NUT_POTAS, NUT_SODIUM, NUT_ZINC, NUT_COPP, NUT_MANG, NUT_SELEN, NUT_VIT_C, NUT_THIAM, NUT_RIBOF, NUT_NIAC, NUT_PANTO, NUT_VIT_B6, NUT_FOLAT_TOT, NUT_FOLIC, NUT_FOLATE_FD, NUT_FOLATE_DFE, NUT_CHOLINE, NUT_VIT_B12, NUT_VIT_A_IU, NUT_VIT_A_RAE, NUT_RETINOL, NUT_ALPHA_CAROT, NUT_BETA_CAROT, NUT_BETA_CRYPT, NUT_LYCOP, NUT_LUT_ZEA, NUT_VIT_E, NUT_VIT_D, NUT_VIT_D_IU, NUT_VIT_K, NUT_FA_SAT, NUT_FA_MONO, NUT_FA_POLY, NUT_CHOLEST, NUT_REFUSE', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'NUT_ID' => 'Nut',
			'NUT_DESC' => 'Nut Desc',
			'NUT_WATER' => 'Nut Water',
			'NUT_ENERG' => 'Nut Energ',
			'NUT_PROT' => 'Nut Prot',
			'NUT_LIPID' => 'Nut Lipid',
			'NUT_ASH' => 'Nut Ash',
			'NUT_CARB' => 'Nut Carb',
			'NUT_FIBER' => 'Nut Fiber',
			'NUT_SUGAR' => 'Nut Sugar',
			'NUT_CALC' => 'Nut Calc',
			'NUT_IRON' => 'Nut Iron',
			'NUT_MAGN' => 'Nut Magn',
			'NUT_PHOS' => 'Nut Phos',
			'NUT_POTAS' => 'Nut Potas',
			'NUT_SODIUM' => 'Nut Sodium',
			'NUT_ZINC' => 'Nut Zinc',
			'NUT_COPP' => 'Nut Copp',
			'NUT_MANG' => 'Nut Mang',
			'NUT_SELEN' => 'Nut Selen',
			'NUT_VIT_C' => 'Nut Vit C',
			'NUT_THIAM' => 'Nut Thiam',
			'NUT_RIBOF' => 'Nut Ribof',
			'NUT_NIAC' => 'Nut Niac',
			'NUT_PANTO' => 'Nut Panto',
			'NUT_VIT_B6' => 'Nut Vit B6',
			'NUT_FOLAT_TOT' => 'Nut Folat Tot',
			'NUT_FOLIC' => 'Nut Folic',
			'NUT_FOLATE_FD' => 'Nut Folate Fd',
			'NUT_FOLATE_DFE' => 'Nut Folate Dfe',
			'NUT_CHOLINE' => 'Nut Choline',
			'NUT_VIT_B12' => 'Nut Vit B12',
			'NUT_VIT_A_IU' => 'Nut Vit A Iu',
			'NUT_VIT_A_RAE' => 'Nut Vit A Rae',
			'NUT_RETINOL' => 'Nut Retinol',
			'NUT_ALPHA_CAROT' => 'Nut Alpha Carot',
			'NUT_BETA_CAROT' => 'Nut Beta Carot',
			'NUT_BETA_CRYPT' => 'Nut Beta Crypt',
			'NUT_LYCOP' => 'Nut Lycop',
			'NUT_LUT_ZEA' => 'Nut Lut Zea',
			'NUT_VIT_E' => 'Nut Vit E',
			'NUT_VIT_D' => 'Nut Vit D',
			'NUT_VIT_D_IU' => 'Nut Vit D Iu',
			'NUT_VIT_K' => 'Nut Vit K',
			'NUT_FA_SAT' => 'Nut Fa Sat',
			'NUT_FA_MONO' => 'Nut Fa Mono',
			'NUT_FA_POLY' => 'Nut Fa Poly',
			'NUT_CHOLEST' => 'Nut Cholest',
			'NUT_REFUSE' => 'Nut Refuse',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('NUT_ID',$this->NUT_ID,true);
		$criteria->compare('NUT_DESC',$this->NUT_DESC,true);
		$criteria->compare('NUT_WATER',$this->NUT_WATER);
		$criteria->compare('NUT_ENERG',$this->NUT_ENERG);
		$criteria->compare('NUT_PROT',$this->NUT_PROT);
		$criteria->compare('NUT_LIPID',$this->NUT_LIPID);
		$criteria->compare('NUT_ASH',$this->NUT_ASH);
		$criteria->compare('NUT_CARB',$this->NUT_CARB);
		$criteria->compare('NUT_FIBER',$this->NUT_FIBER);
		$criteria->compare('NUT_SUGAR',$this->NUT_SUGAR);
		$criteria->compare('NUT_CALC',$this->NUT_CALC);
		$criteria->compare('NUT_IRON',$this->NUT_IRON);
		$criteria->compare('NUT_MAGN',$this->NUT_MAGN);
		$criteria->compare('NUT_PHOS',$this->NUT_PHOS);
		$criteria->compare('NUT_POTAS',$this->NUT_POTAS);
		$criteria->compare('NUT_SODIUM',$this->NUT_SODIUM);
		$criteria->compare('NUT_ZINC',$this->NUT_ZINC);
		$criteria->compare('NUT_COPP',$this->NUT_COPP);
		$criteria->compare('NUT_MANG',$this->NUT_MANG);
		$criteria->compare('NUT_SELEN',$this->NUT_SELEN);
		$criteria->compare('NUT_VIT_C',$this->NUT_VIT_C);
		$criteria->compare('NUT_THIAM',$this->NUT_THIAM);
		$criteria->compare('NUT_RIBOF',$this->NUT_RIBOF);
		$criteria->compare('NUT_NIAC',$this->NUT_NIAC);
		$criteria->compare('NUT_PANTO',$this->NUT_PANTO);
		$criteria->compare('NUT_VIT_B6',$this->NUT_VIT_B6);
		$criteria->compare('NUT_FOLAT_TOT',$this->NUT_FOLAT_TOT);
		$criteria->compare('NUT_FOLIC',$this->NUT_FOLIC);
		$criteria->compare('NUT_FOLATE_FD',$this->NUT_FOLATE_FD);
		$criteria->compare('NUT_FOLATE_DFE',$this->NUT_FOLATE_DFE);
		$criteria->compare('NUT_CHOLINE',$this->NUT_CHOLINE);
		$criteria->compare('NUT_VIT_B12',$this->NUT_VIT_B12);
		$criteria->compare('NUT_VIT_A_IU',$this->NUT_VIT_A_IU);
		$criteria->compare('NUT_VIT_A_RAE',$this->NUT_VIT_A_RAE);
		$criteria->compare('NUT_RETINOL',$this->NUT_RETINOL);
		$criteria->compare('NUT_ALPHA_CAROT',$this->NUT_ALPHA_CAROT);
		$criteria->compare('NUT_BETA_CAROT',$this->NUT_BETA_CAROT);
		$criteria->compare('NUT_BETA_CRYPT',$this->NUT_BETA_CRYPT);
		$criteria->compare('NUT_LYCOP',$this->NUT_LYCOP);
		$criteria->compare('NUT_LUT_ZEA',$this->NUT_LUT_ZEA);
		$criteria->compare('NUT_VIT_E',$this->NUT_VIT_E);
		$criteria->compare('NUT_VIT_D',$this->NUT_VIT_D);
		$criteria->compare('NUT_VIT_D_IU',$this->NUT_VIT_D_IU);
		$criteria->compare('NUT_VIT_K',$this->NUT_VIT_K);
		$criteria->compare('NUT_FA_SAT',$this->NUT_FA_SAT);
		$criteria->compare('NUT_FA_MONO',$this->NUT_FA_MONO);
		$criteria->compare('NUT_FA_POLY',$this->NUT_FA_POLY);
		$criteria->compare('NUT_CHOLEST',$this->NUT_CHOLEST);
		$criteria->compare('NUT_REFUSE',$this->NUT_REFUSE);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}