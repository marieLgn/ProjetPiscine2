<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 2) {
    header("Location: login.php");
    exit();
}

$agent_id = $_SESSION['user_id'];

// Récupérer les rendez-vous à venir
$sql = "SELECT Rendez_vous.Date_heure, Client.Nom, Client.Prenom, Client.No_Telephone
        FROM Rendez_vous
        JOIN Client ON Rendez_vous.Id_Client = Client.Id_Client
        WHERE Rendez_vous.Id_Agent = ? AND Rendez_vous.Date_heure > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$rendez_vous = $stmt->get_result();

// Récupérer les clients suivis par l'agent
$sql = "SELECT DISTINCT Client.Id_Client, Client.Nom, Client.Prenom
        FROM Communiquer
        JOIN Client ON Communiquer.Id_Client = Client.Id_Client
        WHERE Communiquer.Id_Agent = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $agent_id);
$stmt->execute();
$clients = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord agent immobilier</title>
    <link rel="stylesheet" href="styles.css">
    <script>
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
        <div class="title">
        <h1 style="display: flex; align-items: center;">
            <img src="Image\Logo.jpg" style="height: 10%; width: 10%; margin-right: 550px;" alt="Propriété 1"> Omnes Emmobilier
        </h1>
        <nav>
            <a href="index.php">Accueil</a>
            <a href="browse.php">Tout parcourir</a>
            <a href="search.php">Rechercher</a>
            <a href="rendez_vous.php">Rendez-vous</a>
            <a href="login.php">Votre compte</a> 
        </nav>
        </div>
        <div class="icon" onclick="toggleMessenger()">📧</div>
    </div>

    <div class="container">
        <h1>Bienvenue sur votre tableau de bord agent immobilier</h1>

        <div class="table-section">
            <h2>Rendez-vous à venir</h2>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Nom du client</th>
                    <th>Prénom du client</th>
                    <th>Numéro de téléphone</th>
                </tr>
                <?php if ($rendez_vous->num_rows > 0): ?>
                    <?php while ($rdv = $rendez_vous->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo date('d-m-Y', strtotime($rdv['Date_heure'])); ?></td>
                            <td><?php echo date('H:i', strtotime($rdv['Date_heure'])); ?></td>
                            <td><?php echo $rdv['Nom']; ?></td>
                            <td><?php echo $rdv['Prenom']; ?></td>
                            <td><?php echo $rdv['No_Telephone']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">Aucun rendez-vous à venir.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="table-section">
            <h2>Mes clients</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Action</th>
                </tr>
                <?php if ($clients->num_rows > 0): ?>
                    <?php while ($client = $clients->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $client['Nom']; ?></td>
                            <td><?php echo $client['Prenom']; ?></td>
                            <td><a href="view_client.php?id=<?php echo $client['Id_Client']; ?>">Voir le dossier</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">Aucun client suivi pour le moment.</td>
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
            $stmt->bind_param("ii", $agent_id, $agent_id);
            $stmt->execute();
            $result = $stmt->get_result();

            $contact_ids = [];
            while ($row = $result->fetch_assoc()) {
                $contact_id = $row['Id_Sender'] == $agent_id ? $row['Id_Receiver'] : $row['Id_Sender'];
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