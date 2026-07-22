<?php
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Nettoyage et validation des données
		$nom = filter_var(trim($_POST["nom"]), FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		$email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
		$message = htmlspecialchars(trim($_POST["message"]), ENT_QUOTES, 'UTF-8');

		// Validation de l'email
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo "Adresse email invalide.";
			?>
			<br /><br />
			<button onclick="rtn()">Retourner</button>
			<script>
			function rtn() {
			window.history.back();
			}
			</script>
			<?php
			exit;
		}

		// Adresse e-mail de destination
		$destinataire = "hotline@club-inter.net";

		// Sujet du message (échappé pour éviter l'injection)
		$sujet = "Nouveau message de " . $nom;

		// En-têtes du message
		$headers = "From: " . $email . "\r\n";
		$headers .= "Reply-To: " . $email . "\r\n";
		$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

		// Vérifie la clause anti-spam (plus flexible)
		$antiSpam = strtolower(str_replace(['-', ' '], '', trim($_POST["anti-spam"])));
		$validAnswers = ['clubinternet', 'club', 'clubreplugged']; // Plusieurs réponses acceptées

		if (!in_array($antiSpam, $validAnswers)) {
			echo "Veuillez retaper correctement le nom du meilleur des FAI.";
			?>
			<br /><br />
			<button onclick="rtn()">Retourner</button>
			<script>
			function rtn() {
			window.history.back();
			}
			</script>
			<?php
		} else {
			// Envoi du message
			$envoi = mail($destinataire, $sujet, $message, $headers);

			if ($envoi) {
				// Redirection vers la page de confirmation
				header("Location: confirmation.php");
				exit;
			} else {
				echo "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.";
				?>
				<br /><br />
				<button onclick="rtn()">Retourner</button>
				<script>
				function rtn() {
				window.history.back();
				}
				</script>
				<?php
			}
		}
	}
?>