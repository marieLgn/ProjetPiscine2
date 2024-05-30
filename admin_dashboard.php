<?php
session_start();
include('config.php');

if ($_SESSION['user_level'] != 3) {
    header("Location: login_form.php");
    exit();
}

// Récupérer toutes les annonces
$sql = "SELECT * FROM Propriete";
$annonces = $conn->query($sql);

// Récupérer tous les agents
$sql = "SELECT * FROM Agent";
$agents = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord administrateur</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .header {
            width: 100%;
            background-color: #333;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            box-sizing: border-box;
        }

        .header .title {
            text-align: center;
            flex-grow: 1;
        }

        .header .title h1 {
            margin: 0;
        }

        .header .icon, .header .back-button {
            cursor: pointer;
        }

        .header .back-button {
            background-color: #d9534f;
            padding: 10px;
            border: none;
            border-radius: 5px;
            color: white;
        }

        .container {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2 {
            text-align: center;
        }

        .section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .logout-button {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .logout-button:hover {
            background-color: #c9302c;
        }

        .messenger-popup {
            display: none;
            position: fixed;
            right: 20px;
            bottom: 20px;
            width: 600px;
            height: 500px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }

        .messenger-header {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border-radius: 5px 5px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .messenger-header .back-button-conversation {
            background-color: #d9534f;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: none;
        }

        .messenger-body {
            flex: 1;
            max-height: 350px;
            overflow-y: auto;
            padding: 20px;
        }

        .messenger-footer {
            padding: 20px;
            border-top: 1px solid #ccc;
        }

        .messenger-footer input {
            width: 80%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .messenger-footer button {
            width: 18%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007BFF;
            color: white;
            cursor: pointer;
        }

        .conversation-list div {
            cursor: pointer;
            padding: 20px;
            border-bottom: 1px solid #ccc;
        }

        .conversation-list div:hover {
            background-color: #f2f2f2;
        }

        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .message.sent {
            background-color: #007BFF;
            color: white;
            align-self: flex-end;
        }

        .message.received {
            background-color: #f1f1f1;
            color: black;
            align-self: flex-start;
        }

        .form-section {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .form-section input, .form-section textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-section button {
            background-color: #007BFF;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
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
        <button class="back-button">Retour</button>
        <div class="title">
            <h1>Omnes Immobilier</h1>
        </div>
        <div class="icon" onclick="toggleMessenger()">📧</div>
    </div>

    <div class="container">
        <h1>Bienvenue sur votre tableau de bord administrateur</h1>

        <div class="section">
            <h2>Annonces</h2>
            <table>
                <tr>
                    <th>Adresse</th>
                    <th>Ville</th>
                    <th>Code postal</th>
                    <th>Type de propriété</th>
                    <th>Description</th>
                    <th>Nombre de pièces</th>
                    <th>Dimensions</th>
                    <th>Étage</th>
                    <th>Statut</th>
                    <th>Prix</th>
                    <th>Action</th>
                </tr>
                <?php if ($annonces->num_rows > 0): ?>
                    <?php while ($annonce = $annonces->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $annonce['Adresse']; ?></td>
                            <td><?php echo $annonce['Ville']; ?></td>
                            <td><?php echo $annonce['Code_postal']; ?></td>
                            <td><?php echo $annonce['Type_propriete']; ?></td>
                            <td><?php echo $annonce['Description']; ?></td>
                            <td><?php echo $annonce['Nombre_piece']; ?></td>
                            <td><?php echo $annonce['Dimensions']; ?></td>
                            <td><?php echo $annonce['Etage']; ?></td>
                            <td><?php echo $annonce['Statut']; ?></td>
                            <td><?php echo $annonce['Prix']; ?></td>
                            <td>
                                <form method="POST" action="delete_annonce.php" style="display:inline-block;">
                                    <input type="hidden" name="id_propriete" value="<?php echo $annonce['Id_Propriete']; ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="11">Aucune annonce disponible.</td>
                    </tr>
                <?php endif; ?>
            </table>
            <a href="add_annonce.php"><button>Ajouter une annonce</button></a>
        </div>

        <div class="section">
            <h2>Mes Agents</h2>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Bureau</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Spécialité</th>
                    <th>Action</th>
                </tr>
                <?php if ($agents->num_rows > 0): ?>
                    <?php while ($agent = $agents->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $agent['Nom']; ?></td>
                            <td><?php echo $agent['Prenom']; ?></td>
                            <td><?php echo $agent['Bureau']; ?></td>
                            <td><?php echo $agent['No_Telephone']; ?></td>
                            <td><?php echo $agent['Email']; ?></td>
                            <td><?php echo $agent['Specialite']; ?></td>
                            <td>
                                <form method="POST" action="delete_agent.php" style="display:inline-block;">
                                    <input type="hidden" name="id_agent" value="<?php echo $agent['Id_Agent']; ?>">
                                    <button type="submit">Supprimer</button>
                                </form>
                                <form method="GET" action="view_cv.php" style="display:inline-block;">
                                    <input type="hidden" name="id_cv" value="<?php echo $agent['Id_CV']; ?>">
                                    <button type="submit">Voir CV</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">Aucun agent disponible.</td>
                    </tr>
                <?php endif; ?>
            </table>
            <a href="add_agent.php"><button>Ajouter un agent</button></a>
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
            $stmt->bind_param("ii", $_SESSION['user_id'], $_SESSION['user_id']);
            $stmt->execute();
            $result = $stmt->get_result();

            $contact_ids = [];
            while ($row = $result->fetch_assoc()) {
                $contact_id = $row['Id_Sender'] == $_SESSION['user_id'] ? $row['Id_Receiver'] : $row['Id_Sender'];
                if (!in_array($contact_id, $contact_ids)) {
                    $contact_ids[] = $contact_id;
                    $sql_contact = "SELECT Nom, Prenom FROM Client WHERE Id_Client = ? UNION SELECT Nom, Prenom FROM Agent WHERE Id_Agent = ? UNION SELECT Email AS Nom, '' AS Prenom FROM Administrateur WHERE Id_Administrateur = ?";
                    $stmt_contact = $conn->prepare($sql_contact);
                    $stmt_contact->bind_param("iii", $contact_id, $contact_id, $contact_id);
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
