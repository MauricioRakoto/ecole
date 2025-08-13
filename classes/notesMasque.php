<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="col">#</th>
                                            <th class="col">Matricule</th>
                                            <th class="col">Nom & Prénom</th>
                                            <th class="col">Sexe</th>
                                            <th scope="col">DS 1</th>
                                            <th scope="col">DS 2</th>
                                            <th scope="col">Examen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ( $notes as $note ): ?>
                                    <tr>
                                       
                                            <!-- Numéro attribué -->
                                            <th scope="row">
                                                <?= $note['numero'] ?>
                                            </th>

                                             <!-- ID élève -->
                                            <td>
                                                <?= $note['eleve_id'] ?>
                                            </td>

                                            <!-- Nom et Prénom élève -->
                                            <td>
                                                <?= $note['nom_eleve'] ?>
                                                <?= $note['prenom_eleve'] ?>
                                            </td>

                                             <!-- Sexe -->
                                            <td>
                                                <?= $note['sexe_eleve'] ?>
                                            </td>
                                       
                                       
                                            <!-- Nom complet -->
                                            <td>
                                                <?= $note['note'] ?>
                                            </td>
                                        
                                    </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
    
</body>
</html>