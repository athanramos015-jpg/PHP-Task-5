<?php
/**
 * CONTROLLER - receives the request, uses the Faculty model, picks a view.
 */
class FacultyController
{
    private Faculty $model;

    public function __construct()
    {
        $this->model = new Faculty();
    }

    /** GET - list all faculty (with optional search) */
    public function index(): void
    {
        $search = (isset($_GET['q']) && is_string($_GET['q'])) ? trim($_GET['q']) : '';

        $this->render('faculty/index', [
            'title'   => 'Faculty list',
            'faculty' => $this->model->all($search),
            'search'  => $search,
        ]);
    }

    /** GET - empty "add faculty" form */
    public function create(): void
    {
        $this->renderForm(Faculty::defaults(), [], null);
    }

    /** POST - save a new faculty member */
    public function store(): void
    {
        $this->requirePost();

        [$data, $errors] = Faculty::validate($_POST);
        if ($errors) {
            $this->renderForm($data, $errors, null);
            return;
        }

        $this->model->create($data);
        flash_set('success', 'Faculty member added.');
        redirect('index.php');
    }

    /** GET - form filled with an existing record */
    public function edit(): void
    {
        $id  = filter_var($_GET['id'] ?? '', FILTER_VALIDATE_INT);
        $row = $id ? $this->model->find($id) : null;

        if (!$row) {
            flash_set('danger', 'That faculty record could not be found.');
            redirect('index.php');
        }

        $data = array_merge(Faculty::defaults(), $row);
        $data['middle_name'] = (string) ($row['middle_name'] ?? '');
        $data['age']         = (string) $row['age'];
        $data['salary']      = number_format((float) $row['salary'], 2, '.', '');

        $this->renderForm($data, [], (int) $row['id']);
    }

    /** POST - save changes to an existing record */
    public function update(): void
    {
        $this->requirePost();

        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        if (!$id || !$this->model->find($id)) {
            flash_set('danger', 'That faculty record could not be found.');
            redirect('index.php');
        }

        [$data, $errors] = Faculty::validate($_POST);
        if ($errors) {
            $this->renderForm($data, $errors, $id);
            return;
        }

        $this->model->update($id, $data);
        flash_set('success', 'Faculty record updated.');
        redirect('index.php');
    }

    /** POST - delete a record */
    public function delete(): void
    {
        $this->requirePost();

        $id = filter_var($_POST['id'] ?? '', FILTER_VALIDATE_INT);
        if ($id) {
            $this->model->delete($id);
            flash_set('success', 'Faculty record deleted.');
        }
        redirect('index.php');
    }

    /* ----------------------------- helpers ----------------------------- */

    /** Only POST requests with a valid CSRF token may change data. */
    private function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php');
        }
        if (!csrf_valid()) {
            flash_set('danger', 'Your session expired. Please try again.');
            redirect('index.php');
        }
    }

    private function renderForm(array $data, array $errors, ?int $editId): void
    {
        $this->render('faculty/form', [
            'title'  => $editId ? 'Edit faculty' : 'Add faculty',
            'data'   => $data,
            'errors' => $errors,
            'editId' => $editId,
        ]);
    }

    /** Render a view inside the shared layout. */
    private function render(string $view, array $vars = []): void
    {
        extract($vars, EXTR_SKIP);
        $flash = flash_get();

        ob_start();
        require __DIR__ . '/../views/' . $view . '.php';
        $content = ob_get_clean();

        require __DIR__ . '/../views/layout.php';
    }
}
