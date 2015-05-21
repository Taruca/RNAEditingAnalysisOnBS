<?php  
class SiteBean {
	private $chr;
	private $pos;
	private $id;
	private $ref;
	private $alt;
	private $qual;
	private $filter;
	private $info;
	private $gt;
	private $ad;
	private $dp;
	private $gq;
	private $pl;
	private $level = -1;
	private $pvalue = -1;
	private $fdr = -1;
	private $isAlu;

	function SiteBean1($a1, $a2) {
			$this->chr = $a1;
			$this->pos = $a2;
	}

	function SiteBean2($a1, $a2, $a3, $a4, $a5, $a6, $a7, 
		$a8, $a9, $a10, $a11, $a12, $a13, $a14) {
		$this->chr = $a1;
		$this->pos = $a2;
		$this->id = $a3;
		$this->ref = $a4;
		$this->alt = $a5;
		$this->qual = $a6;
		$this->filter = $a7;
		$this->info = $a8;
		$this->gt = $a9;
		$this->ad = $a10;
		$this->dp = $a11;
		$this->gq = $a12;
		$this->pl = $a13;
		$this->isAlu = $a14;
	}

	function getChr() {
		return $this->chr;
	}

	function getIsAlu() {
		return $this->isAlu;
	}

	function setIsAlu($isalu) {
		$this->isAlu = $isalu;
	}

	function getPos() {
		return $this->pos;
	}

	function getId() {
		return $this->id;
	}

	function setId($Id) {
		$this->id = $Id;
	}

	function getRef() {
		return $this->ref;
	}

	function setRef($Ref)
	{
			$this->ref = $Ref;
	}

	function getAlt()
	{
		return $this->alt;
	}

	function setAlt($Alt) {
		$this->alt = $alt;
	}

	function getQual()
	{
		return $this->qual;
	}

	function setQual($Qual) {
        $this->qual = $Qual;
    }

	function getFilter()
	{
		return $this->filter;
	}

	function serFilter($Filter) {
        $this->filter = $Filter;
    }

	function getInfo()
	{
		return $this->info;
	}

	function setInfo($Info) {
        $this->info = $Info;
    }

	function getGt()
	{
		return $this->gt;
	}

	function setGt($Gt) {
        $this->gt = $Gt;
    }

	function getAd()
	{
		return $this->ad;
	}

	function setAd($Ad) {
        $this->ad = $Ad;
    }

	function getDp()
	{
		return $this->dp;
	}

	function setDp($Dp) {
        $this->dp = $Dp;
    }

	function getGq()
	{
		return $this->gq;
	}

	function setGq($Gq) {
        $this->gq = $Gq;
    }

	function getPl()
	{
		return $this->pl;
	}

	function setPl($Pl) {
        $this->pl = $Pl;
    }

	function getPValue()
	{
		return $this->pvalue;
	}

	function setPValue($PValue) {
        $this->pvalue = $PValue;
    }

	function getFdr()
	{
		return $this->fdr;
	}

	function setFdr($Fdr) {
        $this->fdr = $Fdr;
    }

	function getLevel()
	{
		return $this->level;
	}

	function setLevel($Level) {
        $this->level = $Level;
    }
    
}
?>