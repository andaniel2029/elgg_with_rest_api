<?php
return array(
	'APIException:ApiResultUnknown' => "Il risultato dell'API è di un tipo sconosciuto, ciò non dovrebbe mai accadere.",
	'APIException:MissingParameterInMethod' => "Parametro %s mancante nel metodo %s",
	'APIException:ParameterNotArray' => "%s non sembra essere una matrice.",
	'APIException:UnrecognisedTypeCast' => "Tipo sconosciuto nella conversione %s per la variabile '%s' nel metodo '%s'",
	'APIException:InvalidParameter' => "Trovato parametro non valido in '%s' nel metodo '%s'.",
	'APIException:FunctionParseError' => "%s(%s) ha un errore di parsing.",
	'APIException:FunctionNoReturn' => "%s(%s) non ha ritornato valori.",
	'APIException:APIAuthenticationFailed' => "Chiamata di metodo non riuscita in API Authentication",
	'APIException:UserAuthenticationFailed' => "Chiamata di metodo non riuscita in User Authentication",
	'APIException:MethodCallNotImplemented' => "La chiamata del metodo '%s' non è stata implementata.",
	'APIException:FunctionDoesNotExist' => "Impossibile chiamare la funzione del metodo '%s'",
	'APIException:AlgorithmNotSupported' => "L'algoritmo '%s' non è supportato, o è stato disabilitato..",
	'APIException:NotGetOrPost' => "Il metodo di richiesta deve essere o GET o POST",
	'APIException:MissingAPIKey' => "Chiave API mancante",
	'APIException:BadAPIKey' => "Chiave API non corretta",
	'APIException:MissingHmac' => "Header X-Elgg-hmac mancante",
	'APIException:MissingHmacAlgo' => "Header X-Elgg-hmac-algo mancante",
	'APIException:MissingTime' => "Header X-Elgg-time mancante",
	'APIException:MissingNonce' => "Header X-Elgg-nonce mancante",
	'APIException:TemporalDrift' => "X-Elgg-time è troppo lontano nel futuro o nel passato. Errore di epoca.",
	'APIException:NoQueryString' => "Nessun dato nella stringa di ricerca",
	'APIException:MissingPOSTHash' => "Header X-Elgg-posthash mancante",
	'APIException:MissingPOSTAlgo' => "Header X-Elgg-posthash_algo mancante",
	'APIException:MissingContentType' => "Tipo di contenuto mancate per i dati d'invio",
	'SecurityException:APIAccessDenied' => "Spiacenti, l'API di accesso è stata disabilitata dall'amministratore.",
	'SecurityException:NoAuthMethods' => "Impossibile trovare un metodo di autenticazione in grado di gestire questa richiesta API.",
	'SecurityException:authenticationfailed' => "L'utente non può essere autenticato",
	'InvalidParameterException:APIMethodOrFunctionNotSet' => "Nessun metodo o funzione definiti nell'expose_method() della chiamata",
	'InvalidParameterException:APIParametersArrayStructure' => "La struttura della matrice di parametri non è corretta per la chiamata di esposizione del metodo '%s'",
	'InvalidParameterException:UnrecognisedHttpMethod' => "Metodo http non riconosciuto %s per il metodo API '%s'",
	'SecurityException:AuthTokenExpired' => "Il token di autenticazione potrebbe essere mancante, non valido o scaduto.",
	'SecurityException:InvalidPostHash' => "L'hash dei dati POST non è valido - Atteso %s ma ottenuto %s.",
	'SecurityException:DupePacket' => "Firma del pacchetto già vista.",
	'SecurityException:InvalidAPIKey' => "Chiavi API non valida o mancante.",
	'NotImplementedException:CallMethodNotImplemented' => "La chiamata al metodo '%s' non è al momento supportato.",
	'CallException:InvalidCallMethod' => "%s deve essere chiamato usando '%s'",

	'system.api.list' => "Elenca tutte le chiamate API disponibili nel sistema.",
	'auth.gettoken' => "Questa chiamata API permette a un utente di ottenere un token di autenticazione che può essere utilizzato per autenticare successive chiamate API. Passarlo come parametro auth_token",
);