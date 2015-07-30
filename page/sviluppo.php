<h1> Sviluppo Magazzino Data Center </h1>
<hr>

<div style="text-align:justify; padding:20px 120px;">
<p>
	<b>IL MAGAZZINO</b> nasce dall'esigenza di tracciare il materiale in arrivo presso il datacenter di torino, esigenza che si configura in particolar modo
	nello stoccaggio di materiali per scopi futuri. Nasce da un lavoro del gruppo accessi del datacenter di torino, i quali erano improntati alla gestione
	materiale della merce, la gestione logica pertanto è stata affidata a questo progetto. Il lavoro si divide in due progetti: il lato back-end per la base di dati,
	ed il lato front-end per la web application.
</p>
<p>
	<b>L'IDEA</b> è lasciare il carico di lavoro al back-end, quindi inserimenti risposte eccezioni con i dati saranno trasparenti alla web application,
	la quale riceverà solo stream di dati. In ogni fase di lavoro è sempre marcata la natura di separare le due componenti.
</p>
<p>
	<b>LO SVILUPPO</b> ha seguito un approccio per prototipi successivi, formalizzata un'attività e relativi casi d'uso si è passati ad implementazioni minimali testate
	successivamente dal gruppo accessi nell'ordinaria amministrazione. Superato il periodo di test del prototipo si è passati al raffinamento con i nuovi input
	ricevuti per poi ripetere la fase di test via via correggendo il tiro, e completare poi con attivita' di troubleshooting e cleaning up del codice.
</p>
<p>
	<b>IL BACK-END</b> viene sviluppato in mysql, è un insieme di stored procedure e stored function organizzate a livelli logici. Alcune definite per uso interno da
	interfacce private, altre definite per uso esterno che fungono da API o interfacce pubbliche. Oltre a quanto citato e con la stessa logica, il back-end si organizza
	in viste pubbliche e private. La differenza è sottile, le viste private consistono in classiche viste sui dati che raccolgono componenti dalle varie tabelle così come
	in una classica definizione di base di dati relazionale, le viste pubbliche sono costruite su quelle private ed adattano il concetto di entità progettato a specifici
	componenti della web application.
</p>
<p>
	<b>IL FRONT-END</b> vede uno sviluppo in PHP, abbiamo delle pagine che si interfacciano direttamente con le API del progetto database, abbiamo delle sessioni differenziate per
	pagina ed utente, ed abbiamo una struttura ad albero per ogni funzione. Riconoscibili sono due tipi di pagine, quelle con raccolta dati e quelle senza. Fattore comune dei due
	tipi è certamente lo scheletro che prevede delle inizializzazioni, dei test sugli stati di alcune variabili valorizzate da pulsanti, della validazione laddove si rende necessario
	per la raccolta dei dati, e degli eventi di iterazione inserimento o reset a seconda che i dati inseriti siano incompleti, completi o processati. Contestualmente ad ogni pagina di
	visualizzazione dati, vi è la possibilità di esportare in formato pdf quello che si sta visualizzando. Le funzioni della web application sono il carico e lo scarico di merce dal
	magazzino, poichè alla base di ogni transito vi è la tripla composta da fornitore - tipo di documento - numero di documento, classico esempio di carico in conseguenza ad un documento
	di trasporto o ad un ordine di acquisto, nel caso di uno scarico il programma genererà un documento chiamato modulo di scarico che permetterà la conferma della richiesta effettiva
	della merce prelevata. Altre funzionalità sono l'aggiornamento di giacenza, di posizione, di documento nonché ricerca e visualizzazione in transiti e magazzino con o meno dettagli.
	I vari form per raccolta dati sono dotati di due metodi di inserimento dati: inserimento manuale e inserimento guidato. L'inserimento manuale ha priorità su quello guidato, ma è
	preferibile sempre l'inserimento guidato qualora disponibile poiché previene gli errori di battitura.
</p>
<p>
	<b>LA MACCHINA</b> su cui risiedono entrambi progetti è una RedHat 6.2 personalizzata sulla base di una del gruppo implementazione di Torino, vi è stata aggiunta un'installazione LAMP
	con SSL e Kerberos. L'accesso alla web application è permesso grazie al protocollo di autenticazione Kerberos che interroga il server di Active Directory di Poste, dall'altro lato
	l'accesso alla web application è crittografato con SSL SHA-2 256. All'interno del progetto back-end vi è un remapping degli account di rete previsti, con delle permission che oggi
	si differenziano solo per lettura o scrittura. Si prevede anche un livello administrator per la gestione delle permission stesse.
</p>

</div>
<hr>
<p style="text-align:center"><i class="fa fa-code"> <a href="mailto:VILARDID@posteitaliane.it">Dario Vilardi</a></i> e <i class="fa fa-instagram"> <a href="mailto:DALES177@posteitaliane.it">Davide D'Alessio</a></i></p>