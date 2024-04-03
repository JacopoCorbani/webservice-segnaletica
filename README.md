# webservice-segnaletica

_Implementazione di un semplice RESTfull Web Service in PHP_

## Installazione

1. **Creazione del DB**
Accedere al db e lanciare lo script sql presente nella cartella migrations per la creazione del db

2. **Variabili del programma**
Copiare il file configureexample.php nel file config.php quindi aprirlo e valorizzare i parametri di connessione al DB

## data structure

```
{
    "id": value,
    "nome": "value",
    "descrizione": "value",
    "id_categoria": value,
    "percorso_immagine": "value"
}
```

## data return:

1. return all records: GET /segnali

2. return a specific record: GET /segnali/{id}


## Note:
Per avviare il server lanciare dalla cartella del progetto il comando
```
php -S 127.0.0.1:8000 -t public
```

Then connect to 127.0.0.1:8000 with Postman and send http requests. Note: when making PUT and POST requests, make sure to set the Body type to raw, then paste the payload in JSON format and set the content type to JSON (application/json).


