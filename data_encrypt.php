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

    function playfair_algo($data)
    {
        //$data = $para;
        $x_pos = '';
        $dot_pos = '';
        $enc_data = '';
        $key = 'playfair';
        $data = strtolower($data);
        $space_pos = '';
        $cnt=0;
        for($i = 0;$i < strlen($data);$i++)
        {
            if(substr($data,$i,1)==' ')
            {
                $space_pos = $space_pos.($i-$cnt).',';
                $cnt++;
            }
        }
        $data = str_replace(' ','',$data);
        
        $key = str_replace('j','i',$key);
        $data = str_replace('j','i',$data);
        
        //createTable
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
       
        $res = '';
        $cnt=0;
        //remove dots
        $cnt=0;
        for($i=0;$i<strlen($data);$i++)
        {
            if($data{$i}=='.')
            {
               $dot_pos = $dot_pos.(string)($i-$cnt).',';
               $cnt++;
            }
            else    
                $res = $res.$data{$i};
        }
        $data = $res;

        //add x
        $res = '';
        $cnt = 0;
        for($i=0;$i<strlen($data);$i++)
        {
            if($data{$i} == $data{$i+1})
            {
                $res = $res.$data{$i}.'x';
                $x_pos = $x_pos.(string)($i+1+$cnt).',';
                $cnt++;
            }
            else    
                $res = $res.$data{$i};
        }
        $data = $res;

        $data_len = strlen($data);
        if($data_len%2!=0)
        {
            $data = $data."x";
            $x_pos = $x_pos.(string)$data_len.',';
        }
     
        //start of encoding    
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
                $x1 = ($x1+1)%5;
                $x2 = ($x2+1)%5;
                $enc_data = $enc_data.$play_table[$x1][$y1].$play_table[$x2][$y2];
            }
            elseif($x1 == $x2)
            {
               $y1 = ($y1+1)%5;
               $y2 = ($y2+1)%5;
               $enc_data = $enc_data.$play_table[$x1][$y1].$play_table[$x2][$y2];
            }
            else
                $enc_data = $enc_data.$play_table[$x1][$y2].$play_table[$x2][$y1];
            
            $i = $i+$inc;
        }//end of while
        
        $enc_data = $enc_data.'@'.$space_pos.'#'.$x_pos.'%'.$dot_pos;
        #echo "Store data:".$enc_data.'@'.$space_pos;
        return $enc_data;
    }  
?>
