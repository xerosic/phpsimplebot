<?php
        if (strpos($msg, "/users") === 0 and $isadmin) {
            
            $qcp = $db->query("select * from $table where not page = 'disable' and not page='group' and chat_id>0");
            $qcg = $db->query("select * from $table where not page = 'disable' and chat_id<0");
            $cp  = $qcp->rowCount();
            $cg  = $qcg->rowCount();
            
            //morti
            $mqcp = $db->query("select * from $table where page = 'disable' and chat_id>0");
            $mqcg = $db->query("select * from $table where page = 'disable' and chat_id<0");
            $mcp  = $mqcp->rowCount();
            $mcg  = $mqcg->rowCount();
            
            //utenti
            
            $gr  = $db->query("select * from $table where page = 'group' and chat_id>0");
            $gru = $gr->rowCount();
            
            $iscritti = "*👤ISCRITTI AL BOT*";
            $iscritti .= "\n   👤Chat Private: $cp";
            $iscritti .= "\n   👥Chat Gruppi: $cg";
            
            $iscritti .= "\n\n*🔇MORTI*";
            $iscritti .= "\n   👤Chat Private: $mcp";
            $iscritti .= "\n   👥Chat Gruppi: $mcg";
            
            $iscritti .= "\n\n*👥UTENTI SUI GRUPPI*";
            $iscritti .= "\n 👤Utenti: $gru
";
            sm($chatID, $iscritti, false, false, "markdown");
        }
        //post globali
        
        
        if (strpos($msg, "/post") === 0 and $isadmin) {
            $t = array(
                array(
                    array(
                        "text" => "👤 Utenti",
                        "callback_data" => "/2post 1"
                    ),
                    array(
                        "text" => "Gruppi 👥",
                        "callback_data" => "/2post 2"
                    )
                ),
                array(
                    array(
                        "text" => "👤 Utenti e Gruppi 👥",
                        "callback_data" => "/2post 3"
                    )
                )
            );
            
            sm($chatID, "Ok, dove vuoi inviare il messaggio globale?

_Se selezioni gruppi, invia anche nei canali conosciuti._", $t, "inline", 'Markdown');
        }
        
        if (strpos($msg, "/2post") === 0 and $isadmin) {
            $campo = explode(" ", $msg);
            $db->query("update $table set page = 'post $campo[1]' where chat_id = $chatID");
            
            $t = array(
                array(
                    array(
                        "text" => " Annulla",
                        "callback_data" => "/apostannulla"
                    )
                )
            );
            
            cb_reply($cbid, "Ok!", false, $cbmid, "Ok $nome, invia ora il post globale che vuoi inviare.
Formattazione: " . $config['parse_mode'], $t);
            
        }
        
        if (strpos($msg, "/apostannulla") === 0 and $isadmin) {
            cb_reply($cbid, "Ok!", false, $cbmid, "Invio Post annullato");
            $db->query("update $table set page = '' where chat_id = $chatID");
            return;
        }
        if (strpos($u['page'], "post") === 0) {
            if ($msg) {
                //eseguo
                $s    = explode(" ", $u['page']);
                $achi = $s[1];
                $db->query("update $table set page = '' where chat_id = $chatID");
                if ($achi == 1)
                    $q = "where chat_id>0 and not page='group'";
                if ($achi == 2)
                    $q = "where chat_id<0 and not page='group'";
                if ($achi == 3)
                    $q = " where 1 and not page='group'";
                $query = $db->query("SELECT * FROM $table $q");
                $c     = $query->rowCount();
                sm($chatID, "Post in viaggio verso $c utenti.");
                $tot = 0;
                foreach ($query as $user) {
                    $tot++;
                    $id = $user["chat_id"];
                    sm($id, $msg);
                    if ($tot > $c) { //Misura di sicurezza, se vi dà problemi togliete questo if.
                        sm($chatID, "Invio completato, post inviato a $tot utenti.");
                        exit;
                    }
                }
                sm($chatID, "Invio completato, post inviato a $tot utenti.");
                
            } else {
                sm($chatID, "Solo messaggi testuali.");
            }
        }
        
        //ban unban dal bot
        
        if (strpos($msg, "/ban ") === 0 and $isadmin) {
            $campo = explode(" ", $msg);
            if (stripos($campo[1], "@") === 0) {
                $id = id($campo[1]);
            } else {
                $id = $campo[1];
            }
            $db->query("update $table set page = 'ban' where chat_id = $id");
            sm($chatID, "Ho bannato $campo[1] dal bot");
        }
        if (strpos($msg, "/unban ") === 0 and $isadmin) {
            $campo = explode(" ", $msg);
            if (stripos($campo[1], "@") === 0) {
                
                $id = id($campo[1]);
            } else {
                $id = $campo[1];
            }
            $db->query("update $table set page = '' where chat_id = $id");
            sm($chatID, "Ho sbannato $campo[1]");
        }
    }

