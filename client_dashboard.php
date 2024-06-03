<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 1) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Mettre à jour les informations du client
if (isset($_POST['update_profile'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $code_postal = $_POST['code_postal'];
    $pays = $_POST['pays'];
    $adresse_ligne_1 = $_POST['adresse_ligne_1'];
    $adresse_ligne_2 = $_POST['adresse_ligne_2'];
    $ville = $_POST['ville'];

    $sql = "UPDATE Client SET Nom = ?, Prenom = ?, Email = ?, No_Telephone = ?, Code_postal = ?, Pays = ?, Adresse_ligne_1 = ?, Adresse_ligne_2 = ?, Ville = ? WHERE Id_Client = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssi", $nom, $prenom, $email, $telephone, $code_postal, $pays, $adresse_ligne_1, $adresse_ligne_2, $ville, $user_id);
    $stmt->execute();
}

// Annuler un rendez-vous
if (isset($_GET['cancel_rdv'])) {
    $rdv_id = $_GET['cancel_rdv'];

    $sql = "DELETE FROM Rendez_vous WHERE Id_Rendez_vous = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rdv_id);
    $stmt->execute();
}

// Récupérer les informations du client
$sql = "SELECT * FROM Client WHERE Id_Client = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$client = $result->fetch_assoc();

if (!$client) {
    echo "Aucun client trouvé avec cet ID.";
    exit();
}

// Récupérer l'historique des consultations
$sql = "SELECT * FROM Rendez_vous WHERE Id_Client = ? AND Date_heure < NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$consultations = $stmt->get_result();

// Récupérer les rendez-vous à venir
$sql = "SELECT * FROM Rendez_vous WHERE Id_Client = ? AND Date_heure > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$rendez_vous = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord client</title>
    <link rel="stylesheet" href="styles.css">

    <script>
        function toggleEditMode() {
            var formElements = document.querySelectorAll(".profile-section input");
            var editButton = document.getElementById("editButton");
            var updateButton = document.getElementById("updateButton");

            formElements.forEach(function(element) {
                element.disabled = !element.disabled;
            });

            editButton.style.display = editButton.style.display === "none" ? "block" : "none";
            updateButton.style.display = updateButton.style.display === "none" ? "block" : "none";
        }

        function toggleMessenger() {
            var messengerPopup = document.getElementById("messengerPopup");
            messengerPopup.style.display = messengerPopup.style.display === "none" ? "flex" : "none";
        }

        function loadConversation(clientId) {
            document.getElementById("conversationList").style.display = 'none';
            document.getElementById("backButtonConversation").style.display = 'block';
            document.getElementById("receiverId").value = clientId;
            fetch('get_conversation.php?client_id=' + clientId)
                .then(response => response.json())
                .then(data => {
                    var conversationBody = document.getElementById("conversationBody");
                    conversationBody.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(message => {
                            var messageDiv = document.createElement('div');
                            messageDiv.classList.add('message');
                            if (message.Id_Sender == <?php echo $_SESSION['user_id']; ?>) {
                                messageDiv.classList.add('sent');
                            } else {
                                messageDiv.classList.add('received');
                            }
                            messageDiv.textContent = message.Message_Text;
                            conversationBody.appendChild(messageDiv);
                        });
                    } else {
                        conversationBody.innerHTML = '<p>Aucune conversation disponible.</p>';
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                });
        }

        function showConversationList() {
            document.getElementById("conversationList").style.display = 'block';
            document.getElementById("backButtonConversation").style.display = 'none';
            document.getElementById("conversationBody").innerHTML = '';
        }

        function sendMessage() {
            var messageInput = document.getElementById("messageInput");
            var receiverId = document.getElementById("receiverId").value;

            fetch('send_message.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'receiver_id=' + receiverId + '&message=' + messageInput.value
            }).then(response => {
                messageInput.value = '';
                loadConversation(receiverId);
            });
        }
    </script>
</head>
<body>
    <div class="header">
        <!--<button class="back-button">Retour</button>-->
        <div class="title">
            <h1 style="display: flex; align-items: center;">
                <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 550px;" alt="Propriété 1"> Omnes Emmobilier
            </h1>
        </div>
        
        <div class="icon" onclick="toggleMessenger()">📧</div>
        
        
    </div>
    <nav>
            <a href="index.php">Accueil</a>
            <a href="browse.php">Tout parcourir</a>
            <a href="search.php">Rechercher</a>
            <a href="rendez_vous.php">Rendez-vous</a>
            <a href="login.php">Votre compte</a>
        </nav>

    <div class="container">
        
        <h1>Bienvenue sur votre tableau de bord client</h1>

        <div class="profile-section">
            <h2>Mes informations</h2>
            <form method="POST" action="client_dashboard.php">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" value="<?php echo isset($client['Nom']) ? $client['Nom'] : ''; ?>" disabled required>
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" value="<?php echo isset($client['Prenom']) ? $client['Prenom'] : ''; ?>" disabled required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo isset($client['Email']) ? $client['Email'] : ''; ?>" disabled required>
                <label for="telephone">Téléphone:</label>
                <input type="text" id="telephone" name="telephone" value="<?php echo isset($client['No_Telephone']) ? $client['No_Telephone'] : ''; ?>" disabled required>
                <label for="code_postal">Code postal:</label>
                <input type="text" id="code_postal" name="code_postal" value="<?php echo isset($client['Code_postal']) ? $client['Code_postal'] : ''; ?>" disabled required>
                <label for="pays">Pays:</label>
                <input type="text" id="pays" name="pays" value="<?php echo isset($client['Pays']) ? $client['Pays'] : ''; ?>" disabled required>
                <label for="adresse_ligne_1">Adresse ligne 1:</label>
                <input type="text" id="adresse_ligne_1" name="adresse_ligne_1" value="<?php echo isset($client['Adresse_ligne_1']) ? $client['Adresse_ligne_1'] : ''; ?>" disabled required>
                <label for="adresse_ligne_2">Adresse ligne 2:</label>
                <input type="text" id="adresse_ligne_2" name="adresse_ligne_2" value="<?php echo isset($client['Adresse_ligne_2']) ? $client['Adresse_ligne_2'] : ''; ?>" disabled>
                <label for="ville">Ville:</label>
                <input type="text" id="ville" name="ville" value="<?php echo isset($client['Ville']) ? $client['Ville'] : ''; ?>" disabled required>
                <button type="button" id="editButton" onclick="toggleEditMode()">Modifier</button>
                <button type="submit" name="update_profile" id="updateButton" class="edit-button"style="display: none;>Enregistrer</button>
            </form>
        </div>

        <div class="table-section">
            <h2>Historique des consultations</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Agent immobilier</th>
                </tr>
                <?php if ($consultations->num_rows > 0): ?>
                    <?php while ($consultation = $consultations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d-m-Y', strtotime($consultation['Date_heure'])); ?></td>
                            <td><?php echo date('H:i', strtotime($consultation['Date_heure'])); ?></td>
                            <td><?php
                                $agent_id = $consultation['Id_Agent'];
                                $sql = "SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $agent_id);
                                $stmt->execute();
                                $agent_result = $stmt->get_result();
                                $agent = $agent_result->fetch_assoc();
                                echo isset($agent['Nom']) ? $agent['Nom'] . ' ' . $agent['Prenom'] : '';
                            ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Aucun historique de consultations disponible.</td>
                    </tr>
                <?php endif; ?>
            </table>

            <h2>Rendez-vous à venir</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Agent immobilier</th>
                    <th>Action</th>
                </tr>
                <?php if ($rendez_vous->num_rows > 0): ?>
                    <?php while ($rdv = $rendez_vous->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d-m-Y', strtotime($rdv['Date_heure'])); ?></td>
                            <td><?php echo date('H:i', strtotime($rdv['Date_heure'])); ?></td>
                            <td><?php
                                $agent_id = $rdv['Id_Agent'];
                                $sql = "SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $agent_id);
                                $stmt->execute();
                                $agent_result = $stmt->get_result();
                                $agent = $agent_result->fetch_assoc();
                                echo isset($agent['Nom']) ? $agent['Nom'] . ' ' . $agent['Prenom'] : '';
                            ?></td>
                            <td><a href="client_dashboard.php?cancel_rdv=<?php echo $rdv['Id_Rendez_vous']; ?>">Annuler ce RDV</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">Aucun rendez-vous à venir.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <form action="logout.php" method="POST">
            <button type="submit" class="logout-button">Se déconnecter</button>
        </form>
    </div>

    <!-- Messenger Popup -->
    <div class="messenger-popup" id="messengerPopup">
        <div class="messenger-header">
            <button id="backButtonConversation" class="back-button-conversation" onclick="showConversationList()">Retour</button>
            <span>Messagerie</span>
            <button onclick="toggleMessenger()">X</button>
        </div>
        <div class="messenger-body conversation-list" id="conversationList">
            <!-- Liste des conversations -->
            <?php
            $sql = "SELECT DISTINCT Id_Sender, Id_Receiver FROM Messages WHERE Id_Sender = ? OR Id_Receiver = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $user_id, $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $contact_ids = [];
            while ($row = $result->fetch_assoc()) {
                $contact_id = $row['Id_Sender'] == $user_id ? $row['Id_Receiver'] : $row['Id_Sender'];
                if (!in_array($contact_id, $contact_ids)) {
                    $contact_ids[] = $contact_id;
                    $sql_contact = "SELECT Nom, Prenom FROM Client WHERE Id_Client = ? UNION SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ?";
                    $stmt_contact = $conn->prepare($sql_contact);
                    $stmt_contact->bind_param("ii", $contact_id, $contact_id);
                    $stmt_contact->execute();
                    $contact_result = $stmt_contact->get_result();
                    $contact = $contact_result->fetch_assoc();
                    echo "<div onclick=\"loadConversation($contact_id)\">" . (isset($contact['Nom']) ? $contact['Nom'] : '') . " " . (isset($contact['Prenom']) ? $contact['Prenom'] : '') . "</div>";
                }
            }
            ?>
        </div>
        <div class="messenger-body" id="conversationBody">
            <!-- Afficher la conversation ici -->
        </div>
        <div class="messenger-footer">
            <input type="hidden" id="receiverId">
            <input type="text" id="messageInput" placeholder="Votre message">
            <button onclick="sendMessage()">Envoyer</button>
        </div>
    </div>
</body>
</html>