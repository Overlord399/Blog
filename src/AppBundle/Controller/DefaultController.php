<?php

namespace AppBundle\Controller;

use AppBundle\NewsSite\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\NewsSite\ItemQuery;
use Symfony\Component\Debug\Debug;
use AppBundle\NewsSite\Comment;
use AppBundle\NewsSite\CommentQuery;
use AppBundle\NewsSite\User;
use AppBundle\NewsSite\UserQuery;
use Symfony\Component\HttpFoundation\Session\Session;



class DefaultController extends Controller
{
    //Главная страница с пагинацией
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $ItemPager = ItemQuery::create()->paginate(1, 2); //Пэйджеры постов
        return $this->render('default/index.html.twig', [
            "Items"=>$ItemPager->getResults(),
            "Pagers"=>$ItemPager,
            "Number"=>1

        ]);
    }

    //Переход на другие страницы
    /**
     * @Route("/list-items/", name="list-items")
     */
    public function GetNewPageAction(Request $request)
    {
        $Number = $request->get("Number"); // Номер страницы для которой делать пагинацию
        $ItemPager = ItemQuery::create()->paginate($Number, 2);
        return new JsonResponse(
            [
                'html' => $this->renderView('default/list-items.html.twig', [
                    'Items'=>$ItemPager->getResults(),
                    'Pagers'=>$ItemPager,
                    'Number'=>$Number
                    ])
            ]
        );
    }

    //Просмотр единичной страницы
    /**
     * @Route("/single-item/", name="single-item")
     */
    public function ViewPageAction(Request $request)
    {
        $Number = $request->get("number");
        $Item = ItemQuery::create()->findOneById($Number); // Выбранный пост
        $Comments=CommentQuery::create()->findByItemId($Number); // Список комментов под постом
        return $this->render('default/single-item.html.twig', [
            "Item"=>$Item,
            "Comments"=>$Comments
        ]);
    }

    //Пересылка на страницу авторизации
    /**
     * @Route("/authorization-page/", name="authorization-page")
     */
    public function AuthorizationPageAction(Request $request)
    {
        return $this->render('default/authorization.html.twig', [

        ]);
    }

    //Авторизация
    /**
     * @Route("/authorization/", name="authorization")
     */
    public function  AuthorizationAction(Request $request)
    {
         $login=$request->get("login"); //Логин введенный юзером
         $password=$request->get("password"); //Пароль введенный юзером
         $user=UserQuery::create()->findOneByUsername($login); //Пытаемся найти такой логин
         if($user)
         {
             if($user->getPassword()==$password) {
                 $session = new Session(); //Сессия
                 $session->set('username',$login);
                 return JsonResponse([], 200);
             }
         }
             return new JsonResponse(array('message' => ''), 401);

    }

    //Добавления нового коммента
    /**
     * @Route("/setcomment/", name="setcomment")
     */
    public function SetcommentAction(Request $request)
    {
        $session=$request->getSession();
        $comment=$request->get('comment');//Сам коммент
        $item_id=$request->get('item_id');//Внешний ключ поста
        $time = date('d/m/Y');
        $user=UserQuery::create()->findOneByUsername($session->get('username')); //Юзер который оставит коммент
        $data= new Comment();
        $data->setContent($comment);
        $data->setDate($time);
        $data->setUserId($user->getId());
        $data->setItemId($item_id);
        $data->save();// Добавляем все в базу
        return new JsonResponse(
            [
                'html' => $this->renderView('default/newcomment.html.twig', [
                    'Comment'=>$data
                ])
            ]
        );

    }

    //Выход
    /**
     * @Route("/logout/", name="logout")
     */
    public function LogoutAction(Request $request)
    {
        $session=$request->getSession();
        $session->invalidate(); //Заканчиваем сессию
        return $this->redirectToRoute('homepage');
    }

    //Переадресация на страницу добавления поста
    /**
     * @Route("/newpost/", name="newpost")
     */
    public function NewpostAction(Request $request)
    {
        return $this->render('default/newpost.html.twig');
    }

    //Создание поста
    /**
     * @Route("/createpost/", name="createpost")
     */
    public function CreatepostAction(Request $request)
    {
        $session = $request->getSession();
        $name = $request->get('name');
        $description = $request->get('description');
        $time = date('d/m/Y');
        $user = UserQuery::create()->findOneByUsername($session->get('username'));
        $data = new Item();
        $data->setTitle($name);
        $data->setDescription($description);
        $data->setUserId($user->getId());
        $data->setDate($time);
        $data->save(); //Добавляем новый пост
        return new JsonResponse(
            [
                'id'=>$data->getId()
            ]
        );
    }
}

