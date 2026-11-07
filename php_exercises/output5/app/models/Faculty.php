<?php
/**
 * MODEL - talks to the `faculty` table and validates faculty data.
 */
class Faculty
{
    public const GENDERS = ['Male', 'Female', 'Other'];

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public static function defaults(): array
    {
        return [
            'first_name'  => '',
            'middle_name' => '',
            'last_name'   => '',
            'age'         => '',
            'gender'      => '',
            'address'     => '',
            'position'    => '',
            'salary'      => '',
        ];
    }

    /**
     * Validate raw input. Returns [cleanData, errors].
     */
    public static function validate(array $input): array
    {
        $data = self::defaults();
        foreach ($data as $key => $default) {
            $data[$key] = (isset($input[$key]) && is_string($input[$key])) ? trim($input[$key]) : '';
        }

        $errors      = [];
        $namePattern = '/^[\p{L}][\p{L} .\'\-]{1,49}$/u';

        if ($data['first_name'] === '') {
            $errors['first_name'] = 'First name is required.';
        } elseif (!preg_match($namePattern, $data['first_name'])) {
            $errors['first_name'] = 'Use letters only (2 to 50 characters).';
        }

        // Middle name is optional
        if ($data['middle_name'] !== '' && !preg_match($namePattern, $data['middle_name'])) {
            $errors['middle_name'] = 'Use letters only (2 to 50 characters), or leave blank.';
        }

        if ($data['last_name'] === '') {
            $errors['last_name'] = 'Last name is required.';
        } elseif (!preg_match($namePattern, $data['last_name'])) {
            $errors['last_name'] = 'Use letters only (2 to 50 characters).';
        }

        if ($data['age'] === '') {
            $errors['age'] = 'Age is required.';
        } else {
            $age = filter_var($data['age'], FILTER_VALIDATE_INT, ['options' => ['min_range' => 18, 'max_range' => 100]]);
            if ($age === false) {
                $errors['age'] = 'Enter a whole number from 18 to 100.';
            } else {
                $data['age'] = (string) $age;
            }
        }

        if (!in_array($data['gender'], self::GENDERS, true)) {
            $errors['gender'] = 'Please select a gender.';
        }

        if ($data['address'] === '') {
            $errors['address'] = 'Address is required.';
        } elseif (strlen($data['address']) < 5 || strlen($data['address']) > 255) {
            $errors['address'] = 'Address must be 5 to 255 characters.';
        }

        if ($data['position'] === '') {
            $errors['position'] = 'Position is required.';
        } elseif (strlen($data['position']) < 2 || strlen($data['position']) > 100) {
            $errors['position'] = 'Position must be 2 to 100 characters.';
        }

        if ($data['salary'] === '') {
            $errors['salary'] = 'Salary is required.';
        } elseif (!preg_match('/^\d{1,7}(\.\d{1,2})?$/', $data['salary']) || (float) $data['salary'] <= 0) {
            $errors['salary'] = 'Enter an amount greater than 0 (up to 7 digits, 2 decimals).';
        }

        return [$data, $errors];
    }

    public function all(string $search = ''): array
    {
        $sql    = 'SELECT id, first_name, middle_name, last_name, age, gender, address, position, salary
                     FROM faculty';
        $params = [];

        if ($search !== '') {
            $sql .= ' WHERE first_name LIKE :q1 OR middle_name LIKE :q2 OR last_name LIKE :q3
                         OR position LIKE :q4 OR address LIKE :q5';
            $like   = '%' . $search . '%';
            $params = [':q1' => $like, ':q2' => $like, ':q3' => $like, ':q4' => $like, ':q5' => $like];
        }

        $sql .= ' ORDER BY last_name ASC, first_name ASC';
        $st = $this->db->prepare($sql);
        $st->execute($params);
        return $st->fetchAll();
    }

    public function find(int $id): ?array
    {
        $st = $this->db->prepare(
            'SELECT id, first_name, middle_name, last_name, age, gender, address, position, salary
               FROM faculty WHERE id = :id'
        );
        $st->execute([':id' => $id]);
        $row = $st->fetch();
        return $row ?: null;
    }

    public function create(array $d): int
    {
        $st = $this->db->prepare(
            'INSERT INTO faculty (first_name, middle_name, last_name, age, gender, address, position, salary)
             VALUES (:first_name, :middle_name, :last_name, :age, :gender, :address, :position, :salary)'
        );
        $st->execute($this->bindings($d));
        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $d): bool
    {
        $st = $this->db->prepare(
            'UPDATE faculty
                SET first_name = :first_name, middle_name = :middle_name, last_name = :last_name,
                    age = :age, gender = :gender, address = :address,
                    position = :position, salary = :salary
              WHERE id = :id'
        );
        $params       = $this->bindings($d);
        $params[':id'] = $id;
        return $st->execute($params);
    }

    public function delete(int $id): bool
    {
        $st = $this->db->prepare('DELETE FROM faculty WHERE id = :id');
        return $st->execute([':id' => $id]);
    }

    private function bindings(array $d): array
    {
        return [
            ':first_name'  => $d['first_name'],
            ':middle_name' => $d['middle_name'] === '' ? null : $d['middle_name'],
            ':last_name'   => $d['last_name'],
            ':age'         => (int) $d['age'],
            ':gender'      => $d['gender'],
            ':address'     => $d['address'],
            ':position'    => $d['position'],
            ':salary'      => number_format((float) $d['salary'], 2, '.', ''),
        ];
    }
}
