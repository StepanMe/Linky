<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\URL;
use Hashids\Hashids;

class ShortenerController extends Controller
{
    /**
     * @Route("/", name="home_page")
     */
    public function indexAction(Request $request)
    {
        // Получаем значение переменной fullUrl
        $long_url = $request->request->get('long_url');

        // Если в переменной что-то есть
        if (isset($long_url) && !empty($long_url)){
            // Смотрим, есть ли в БД длинная ссылка
            $url = $this->getDoctrine()
                ->getRepository('AppBundle:URL')
                ->findOneBy(array('longUrl' => $long_url));

            // Если ссылки нет
            if (empty($url)){
                // Создаём объект
                $url = new URL();
                $url->setLongUrl($long_url);

                $em = $this->getDoctrine()->getManager();
                $em->persist($url);
                $em->flush();

                $id = $url->getId();
                $hashids = new Hashids();
                $short_url = $hashids->encode($id);

                $url->setShortUrl($short_url);
                $em->persist($url);
                $em->flush();

                // Добавили ссылку, результат = 1
                $result = 1;
                return $this->render('index.html.twig',array('result' => $result,'short_url' => $short_url));
            }
            // Если ссылка есть
            else{
                $short_url = $url->getShortUrl();
                $result = 0;
                return $this->render('index.html.twig',array('result' => $result,'short_url' => $short_url));
            }
        }
        else{
            return $this->render('index.html.twig');
        }
    }

    /**
     * @Route("/{short_url}", name="redirect_page")
     */
    public function redirectAction($short_url)
    {
        # Проверяем, что короткая ссылка содержит только разрешённые символы
        if (preg_match('/^[0-9a-zA-Z]+$/',$short_url) == 1){
            // Смотрим, есть ли в БД длинная ссылка
            $url = $this->getDoctrine()
                ->getRepository('AppBundle:URL')
                ->findOneBy(array('shortUrl' => $short_url));

            // Если ссылки нет
            if (empty($url)){
                $result = -1;
                return $this->render('index.html.twig',array('result' => $result,'short_url' => $short_url));
            }
            // Если ссылка есть
            else{
                $fullUrl = $url->getLongUrl();
                return $this->redirect($fullUrl,301);
            }
        }
    }
}
