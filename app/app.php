<?php

    require_once __DIR__."/../vendor/autoload.php";
    require_once __DIR__."/../src/Task.php";
    require_once __DIR__."/../src/Category.php";

    //session_start();
    //if (empty($_SESSION['list_of_tasks'])) {
    //    $_SESSION['list_of_tasks'] = array();
    //}



    $app = new Silex\Application();
    $app['debug'] = true;
    $server = 'mysql:host=localhost;dbname=to_do';
    $username = 'root';
    $password = 'root';
    $DB = new PDO($server, $username, $password);

    use Symfony\Component\HttpFoundation\Request;
    Request::enableHttpMethodParameterOverride();

    $app->register(new Silex\Provider\TwigServiceProvider(), array(
        'twig.path' => __DIR__.'/../views'
    ));

    $app->get("/", function() use ($app) {
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/tasks", function() use ($app) {
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/categories", function() use ($app) {
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $task = new Task($description, $id = null, $due_date);
        $task->save();
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });

    $app->post("/tasks", function() use ($app) {
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $task = new Task($description, $id = null, $due_date);
        $task->save();
        return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    });

    $app->get("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'tasks' => Task::getAll(), 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    $app->post("/categories", function() use ($app) {
        $category = new Category($_POST['name']);
        $category->save();
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->get("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });

    $app->post("/add_tasks", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $category->addTask($task);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'categories' => Category::getAll(), 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    });

    $app->post("/add_categories", function() use ($app) {
        $category = Category::find($_POST['category_id']);
        $task = Task::find($_POST['task_id']);
        $task->addCategory($category);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'tasks' => Task::getAll(), 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    $app->post("/delete_tasks", function() use ($app) {
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/delete_categories", function() use ($app) {
        Category::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->post("/delete_all", function() use ($app) {
        Category::deleteAll();
        Task::deleteAll();
        return $app['twig']->render('index.html.twig');
    });

    $app->get("/categories/{id}/edit", function($id) use ($app) {
        $category = Category::find($id);
        return $app['twig']->render('category_edit.html.twig', array('category' => $category));
    });

    $app->patch("/categories/{id}", function($id) use ($app) {
        $name = $_POST['name'];
        $category = Category::find($id);
        $category->update($name);
        return $app['twig']->render('category.html.twig', array('category' => $category, 'all_tasks' => Task::getAll(), 'tasks' => $category->getTasks()));
    });

    $app->get("/tasks/{id}/edit", function($id) use ($app) {
        $task = Task::find($id);
        return $app['twig']->render('task_edit.html.twig', array('task' => $task, 'all_categories' => Category::getAll()));
    });

    $app->patch("/tasks/{id}", function($id) use ($app) {
        $description = $_POST['description'];
        $due_date = $_POST['due_date'];
        $completed = $_POST['completed'];
        $task = Task::find($id);
        $task->update($description, $due_date, $completed);
        return $app['twig']->render('task.html.twig', array('task' => $task, 'tasks' => Task::getAll(), 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    });

    $app->delete("/categories/{id}", function($id) use ($app) {
        $category = Category::find($id);
        $category->delete();
        return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    });

    $app->delete("/tasks/{id}", function($id) use ($app) {
        $task = Task::find($id);
        $task->delete();
        return $app['twig']->render('index.html.twig');
    });


    // $app->get("/tasks", function() use ($app) {
    //
    //     return $app['twig']->render('tasks.html.twig', array('tasks' => Task::getAll()));
    //
    // });
    //
    // $app->get("/categories", function() use ($app) {
    //     return $app['twig']->render('categories.html.twig', array('categories' => Category::getAll()));
    // });

    // $app->get("/categories/{id}", function($id) use ($app) {
    //     $category = Category::find($id);
    //     return $app['twig']->render('category.html.twig', array('category' => $category, 'tasks' => $category->getTasks(), 'all_tasks' => Task::getAll()));
    // });
    //
    //
    //
    // $app->post("/tasks", function() use ($app) {
    //     $description = $_POST['description'];
    //     $due_date = $_POST['due_date'];
    //     $task = new Task($description, $id = null, $due_date);
    //     $task->save();
    //     $category = Category::find($category_id);
    //     return $app['twig']->render('task.html.twig', array('task' => $task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    // });
    //
    // $app->get("/tasks/{id}", function($id) use ($app) {
    //     $task = Task::find($id);
    //     return $app['twig']->render('task.html.twig', array('task' => $task, 'categories' => $task->getCategories(), 'all_categories' => Category::getAll()));
    // });
    //
    // $app->post("/categories", function() use ($app) {
    //     $category = new Category($_POST['name']);
    //     $category->save();
    //     return $app['twig']->render('index.html.twig', array('categories' => Category::getAll()));
    // });
    //
    //
    //
    // $app->get("/all_tasks", function() use ($app) {
    //     return $app['twig']->render('all_tasks.html.twig', array('tasks' => Task::getAll(), 'categories' => Category::getAll()));
    // });

    return $app;
 ?>
