<?php
    /*********************************************************************/
    /*   date_widget.php                                                 */
    /*   renders a date widget with a date as parameter or the           */
    /*   current date otherwise                                          */
    /*   it accepts a name for the widget as well                        */
    /*                                                                   */
    /*********************************************************************/
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*                                                                   */
    /*********************************************************************/
    /*   Ronald Jaramillo   -   DATE                                     */
    /*                                                                   */
    /*   V I Z I O N   F A C T O R Y   N E W M E D I A                   */
    /*   Vermundsgade 40C - 2100 København Ø - Danmark                   */
    /*   Tel : +45 39 29  25 11 - Fax: +45 39 29 80 11                   */
    /*   ronald@vizionfactory.dk - www.vizionfactory.dk                  */
    /*                                                                   */
    /*********************************************************************/

class date_widget
{
    var $name;
    var $day;
    var $month;
    var $year;
    var $hour;
    var $minute;
    var $second;

    function date_widget( $name="date_widget",$day=0,$month=0,$year=0,$hour='',$minute='',$second='')
    {
        $this->name  = $name;

        //date
        $this->day   = ( $day   )? $day:date("d");
        $this->month = ( $month )? $month:date("m");
        $this->year  = ( $year  )? $year:date("Y");

        //time
        $this->hour  = ( $hour  )? $hour:date("H");
        if( !$hour && is_numeric( $hour ) ) $this->hour = '0'; 
        $this->minute  = ( $minute  )? $minute:date("i");
        if( !$minute && is_numeric( $minute ) ) $this->minute = '0'; 
        $this->second  = ( $second  )? $second:date("s");
        if( !$second && is_numeric( $second ) ) $this->second = '0'; 
    }
    function render()
    {
        $day = $this->day();
        $month = $this->month();
        $year= $this->year();
        return $day.$month.$year;
    }
    function renderTime()
    {
      $hour   = $this->hour();
      $minute = $this->minute();
      $second = $this->second();
      return $hour.$minute.$second;
    }

    function renderDateTime()
    {
      return $this->render().' '.$this->renderTime();
    }
    function day()
    {
        $str = '<select name="day_'. $this->name .'">';

        for($i=1;$i< 32 ;$i++)
        {
            ($i==$this->day)?$selected = " selected ":$selected="";
            $str.= '<option name="'.$i.'" value="'. $i .'" '. $selected .'>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function month()
    {
        $str = '<select name="month_'. $this->name .'">';

        for($i=1;$i< 13 ;$i++)
        {
            ($i==$this->month)?$selected = " selected ":$selected="";
            $str.= '<option name="'.$i.'" value="'. $i .'" '. $selected .'>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function year()
    {
        $str = '<select name="year_'. $this->name .'">';

        $start_year = $this->year - 2 ;
        $end_year = $this->year + 4;

        for($i= $start_year ;$i< $end_year ;$i++)
        {
            ($i==$this->year)?$selected = " selected ":$selected="";
            $str.= '<option name="'.$i.'" value="'. $i .'" '. $selected .'>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function hour()
    {
        $str = '<select name="hour_'. $this->name .'">';

        for($i= 0 ;$i< 24 ;$i++)
        {
            $str.= '<option value="'. $i .'" ';
            $str.= ( $i == $this->hour )?'selected':'';
            $str.= '>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function minute()
    {
        $str = '<select name="minute_'. $this->name .'">';

        for($i= 0 ;$i< 60 ;$i++)
        {
            $str.= '<option value="'. $i .'" ';
            $str.= ( $i == $this->minute )?'selected':'';
            $str.= '>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function second()
    {
        $str = '<select name="second_'. $this->name .'">';

        for($i= 0 ;$i< 60 ;$i++)
        {
            $str.= '<option value="'. $i .'" ';
            $str.= ( $i == $this->second )?'selected':'';
            $str.= '>'.$i.'</option>'."\n";
        }
        $str.= '</select>';

        return $str;
    }
    function setName($name)
    {
        $this->name = $name;
    }
    function setDate($day = 0,$month = 0,$year = 0 )
    {
        if( !$day ) $day = date("d");
        if( !$month ) $month = date("m");
        if( !$year ) $year = date("Y");

        $this->day = $day;
        $this->month = $month;
        $this->year = $year;
    }
}

    //usage
    //$widget = new date_widget("test",3,1,2000);
    //echo $widget->render();
?>
