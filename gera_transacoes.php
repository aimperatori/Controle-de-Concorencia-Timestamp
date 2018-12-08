<?php

$transacoes = $_POST['t'];

$t = trataEntrada($transacoes);

echo'<pre>';
// echo 'Array tratado<br>';
// print_r($t);

$h = geraHistoria($t);
echo '<br>HSTORIA<br>';
print_r($h);


$x = array('r' => 0,
           'w' => 0);

$y = array('r' => 0,
           'w' => 0);

$z = array('r' => 0,
           'w' => 0);


$h = escalonador($h);

print_r($h);


// ESCALONADOR
function escalonador($h) {
    global $x, $y, $z;
    
    $i = 0;
    
    while($i <= lastIndex($h)){
        
        if (isset($h[$i])){
            $elemento = $h[$i];
            
            $rw = $elemento[0];
        
            switch ($elemento[1]) {
                case 'x':
                    if($elemento[0] == 'r') {
                        echo 'leitura: ';
                        
                        $ts = $x['w'];
                        
                        if($elemento['timestamp'] < $ts){
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        }else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o read
                            // ajusta o ts
                            $x["$rw"] = $elemento['timestamp'];
                        }
                    } else if($elemento[0] == 'w') {
                        echo 'escrita: ';
                        
                        $ts = $x['r'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                            echo '<br>';continue;
                        }
                        
                        $ts = $x['w'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        } else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o write
                            // ajusta o ts
                            $x["$rw"] = $elemento['timestamp'];
                        }
                    }
                    echo '<br>';
                    break;
                case 'y':
                    if($elemento[0] == 'r') {
                        echo 'leitura: ';
                        
                        $ts = $y['w'];
                        
                        if($elemento['timestamp'] < $ts){
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        }else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o read
                            // ajusta o ts
                            $y["$rw"] = $elemento['timestamp'];
                        }
                    } else if($elemento[0] == 'w') {
                        echo 'escrita: ';
                        
                        $ts = $y['r'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                            echo '<br>';continue;
                        }
                        
                        $ts = $y['w'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        } else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o write
                            // ajusta o ts
                            $y["$rw"] = $elemento['timestamp'];
                        }
                    }
                    echo '<br>';
                    break;
                case 'z':
                    if($elemento[0] == 'r') {
                        echo 'leitura: ';
                        
                        $ts = $z['w'];
                        
                        if($elemento['timestamp'] < $ts){
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        }else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o read
                            // ajusta o ts
                            $z["$rw"] = $elemento['timestamp'];
                        }
                    } else if($elemento[0] == 'w') {
                        echo 'escrita: ';
                        
                        $ts = $z['r'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                            echo '<br>';continue;
                        }
                        
                        $ts = $z['w'];
                        
                        if($elemento['timestamp'] < $ts) {
                            echo 'rejeit '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            $h = abortaTransacao($h, $elemento['timestamp']);
                        } else {
                            echo 'aceita '.$elemento[0] .' - '.$elemento[1].' ts: '. $elemento['timestamp'];
                            // executa o write
                            // ajusta o ts
                            $z["$rw"] = $elemento['timestamp'];
                        }
                    }
                    echo '<br>';
                    break;
            }
        }
        $i++;
    }
    
    return $h;
}

function trataEntrada($transacoes) {
    // remove inputs nao usados
    foreach ($transacoes as $key => $value) {
        if(empty($value)){
            unset($transacoes[$key]);
        }
    }
    
    // retira possiveis espaços em brancos inseridos
    foreach ($transacoes as $key => $value){
        $transacoes[$key] = trim($transacoes[$key]);
    }
    
    // transforma a string em array 
    $i=0;
    foreach ($transacoes as $transacao) {
        $elementos = explode(' ', $transacao);
        $parte = array();
        $timestamp = microtime(true);
        foreach ($elementos as $elemento) {
            $parte[] = array_merge(array('timestamp' => $timestamp),  explode('-', $elemento));
        }
        $tratado[$i]['transacao'] = $parte;
        sleep(1);
        $i++;
    }
    
    return $tratado;
}

function geraHistoria($t) {
    
    $qtd = count($t);
    
    while(!empty($t[0]['transacao']) || !empty($t[1]['transacao']) || !empty($t[2]['transacao']) ||
          !empty($t[3]['transacao']) || !empty($t[4]['transacao'])) {
       
        $i = rand(0, $qtd-1);
        
        // pega o primeiro elemento do array
        if(!empty($t[$i]['transacao'][0])){
            $atual = $t[$i]['transacao'][0];
            // remove o elemento no inicio
            array_shift($t[$i]['transacao']);
            
            $h[] = $atual;
        }
    }
    
    return $h;
}

function abortaTransacao($h, $ts) {
    foreach ($h as $key => $value) {
        if($value['timestamp'] == $ts) {
            $final[] = $h[$key];
            unset($h[$key]);
        }
    }
    
    // INSERE A TRANSAÇÃO ABORTADA NO FINAL DA HISTÓRIA
    $times = microtime(true);
    foreach ($final as $f) {
        $f['timestamp'] = $times;
        $h[] = $f;
    }
    sleep(1);
    
    return $h;
}

function lastIndex($h) {
    foreach ($h as $key => $value) {
        $k = $key;
    }
    
    return $k;
}

