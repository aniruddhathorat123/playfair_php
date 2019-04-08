<?php
    function check_table($ptable,$ch)
    {
        for($i1 = 0;$i1<5;$i1++)
        {
            for($i2 = 0;$i2<5;$i2++)
            {
                if($ptable[$i1][$i2]==$ch)
                {
                    return '0';
                }
            }
        }
        return '1';
    }

    function get_loc($ch,$ptable)
    {
        $res = '';
        for($i=0;$i<5;$i++)
        {
            for($j=0;$j<5;$j++)
            {
                if($ptable[$i][$j]==$ch)
                {
                  $res = (string)$i.(string)$j;
                  return $res;
                }  
            }
        }
    }

    function remove_x($dt,$xp)
    {
        $res = '';
        $pos = '';
        $n = -1;
        $x= 0;
        $x_len = strlen($xp);
       
        for($x = 0;$xp{$x}!=',';$x++)
            $pos = $pos.$xp{$x};

        $n = (int)$pos;
        $x++;
        
        for($i = 0;$i<strlen($dt);$i++)
        {
            if(($i == $n))
            {
                // && ($dt{$i}=='x')
                $pos = '';
                if($x>=$x_len)
                {
                    $n = -1;
                    continue;
                }
                while($xp{$x}!=',')
                {
                    $pos = $pos.$xp{$x};
                    $x++;
                }
                $n = (int)$pos;
                $x++;
                
            }
            else
                $res = $res.$dt{$i};
        }
        return $res;
    }

    function add_dot($dt,$dp)
    {
        $res = '';
        $pos = '';
        $n = -1;
        $d=0;
        
        $d_len = strlen($dp);

        for($d = 0;$dp{$d}!=',';$d++)
            $pos = $pos.$dp{$d};

        $n = (int)$pos;
        $d++;
        
        for($i = 0;$i<strlen($dt);$i++)
        {
            if($i == $n)
            {
                $res = $res.'.';
                $res = $res.$dt{$i};
                $pos = '';
                if($d>=$d_len)
                {
                    $n=-1;
                    continue;
                }
                while($dp{$d}!=',')
                {
                    $pos = $pos.$dp{$d};
                    $d++;
                }
                $n = (int)$pos;
                $d++;
            }
            else
                $res = $res.$dt{$i};
        }
        $res = $res.'.';
        return $res;
    }

    function add_spaces($dt,$sp)
    {
        $res = '';
        $pos = '';
        $n = -1;
        $s =0;
    
        for($s=0;$sp{$s}!=',';$s++)
            $pos = $pos.$sp{$s};

        $n = (int)$pos;
        $s++;
        
        for($i = 0;$i<strlen($dt);$i++)
        {
            if($i == $n)
            {
                $res = $res.' ';
                $res = $res.$dt{$i};
                $pos = '';
                if($s>=strlen($sp))
                {
                    $n=-1;
                    continue;
                }
                while($sp{$s}!=',')
                {
                    $pos = $pos.$sp{$s};
                    $s++;
                }
                $n = (int)$pos;
                $s++;
            }
            else
                $res = $res.$dt{$i};    
        }
        return $res;    
    }

    function playfair_dec($para)
    {
        $key = 'playfair';
        $dec_data = '';
        $data = '';
        $space_pos = '';
        $x_pos = '';
        $dot_pos = '';
        $i=0;
        //get encryped data
        while($para{$i} != '@')
        {
            $data = $data.$para{$i};
            $i++;
        }
        $i++;

        //get postions of spaces
        while($para{$i} != '#')
        {
            $space_pos = $space_pos.$para{$i};
            $i++;
        }
        $i++;

        //get position of extra X
        while($para{$i} != '%')
        {
            $x_pos = $x_pos.$para{$i};
            $i++;
        }
        $i++;

        //get postion of extra dot
        while($i<strlen($para))
        {
            $dot_pos = $dot_pos.$para{$i};
            $i++;
        }

        $play_table = array
        (
            array('0','0','0','0','0'),
            array('0','0','0','0','0'),
            array('0','0','0','0','0'),
            array('0','0','0','0','0'),
            array('0','0','0','0','0')
        );
         
        $spaces = array(00,01,02,03,04,10,11,12,13,14,20,21,22,23,24,30,31,32,33,34,40,41,42,43,44);

        $key = $key."abcdefghiklmnopqrstuvwxyz";
        $len = count($spaces);
        
        $i=0;
        $pos=0;
       
        while($i<$len)
        {
            if(check_table($play_table,$key{$pos})=='1')
            {
                $row = $spaces[$i]/10;
                $col = fmod($spaces[$i],10);
                $play_table[$row][$col]=$key{$pos};
                $i++;
            }
            $pos++;
         
        }//end of table creation
        //print_r($play_table);

        //start decoding
        $data_len = strlen($data);
        $i=0;
        $ch1='';
        $ch2='';
        $inc=0;
       
        while($i<$data_len)
        {
            $ch1 = $data{$i};
            $ch2 = $data{$i+1};
            $inc = 2;
                
            $x1 = -1;
            $x2 = -1;
            $y1 = -1;
            $y2 = -1;
            $res = '';

            $res = get_loc($ch1,$play_table);
            $x1 = (int)substr($res,0,1);
            $y1 = (int)substr($res,1,1);

            $res = get_loc($ch2,$play_table);
            $x2 = (int)substr($res,0,1);
            $y2 = (int)substr($res,1,1);
            
            if($y1 == $y2)
            {
                $x1 = ($x1-1);
                $x2 = ($x2-1);
                if($x1<0)
                    $x1=4;
                if($x2<0)
                    $x2=4;
                $dec_data = $dec_data.$play_table[$x1][$y1].$play_table[$x2][$y2];
            }
            elseif($x1 == $x2)
            {
                $y1 = ($y1-1);
                $y2 = ($y2-1);
                if($y1<0)
                    $y1=4;
                if($y2<0)
                    $y2=4;
                $dec_data = $dec_data.$play_table[$x1][$y1].$play_table[$x2][$y2];
            }
            else
                $dec_data = $dec_data.$play_table[$x1][$y2].$play_table[$x2][$y1];
            
            $i = $i+$inc;
        }
        //echo $dec_data;

        $dec_data = remove_x($dec_data,$x_pos);
        //echo $dec_data;
        
        $dec_data = add_dot($dec_data,$dot_pos);
        //echo $dec_data;

        $dec_data = add_spaces($dec_data,$space_pos);
        //echo $dec_data;
        return $dec_data;
    }

?>
