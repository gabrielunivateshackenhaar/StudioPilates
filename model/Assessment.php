<?php
// model/Assessment.php

class Assessment {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    // Busca a avaliação mais recente de um usuário.
    public function getByUserId(int $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM assessments WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

     // Salva (Cria ou Atualiza) os dados da Anamnese preenchidos pelo Aluno.
     // Recebe um array com: profession, laterality, height, weight, diagnosis, complaint.
    public function saveAnamnesis(int $userId, array $data) {
        // Verifica se já existe uma ficha para este usuário
        $existing = $this->getByUserId($userId);

        if ($existing) {
            // UPDATE: Atualiza a ficha existente
            $stmt = $this->pdo->prepare("
                UPDATE assessments 
                SET profession = ?, laterality = ?, height = ?, weight = ?, diagnosis = ?, complaint = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $data['profession'],
                $data['laterality'],
                $data['height'],
                $data['weight'],
                $data['diagnosis'],
                $data['complaint'],
                $existing['id']
            ]);
        } else {
            // INSERT: Cria uma nova ficha
            $stmt = $this->pdo->prepare("
                INSERT INTO assessments 
                (user_id, profession, laterality, height, weight, diagnosis, complaint)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([
                $userId,
                $data['profession'],
                $data['laterality'],
                $data['height'],
                $data['weight'],
                $data['diagnosis'],
                $data['complaint']
            ]);
        }
    }
    
    // Método para o Admin salvar a parte técnica (Postural)
    // O $json é uma string JSON com todos os checkboxes marcados.
    public function savePosturalData(int $id, string $json) {
        $stmt = $this->pdo->prepare("UPDATE assessments SET postural_data = ? WHERE id = ?");
        return $stmt->execute([$json, $id]);
    }
}