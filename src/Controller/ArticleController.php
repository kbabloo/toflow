<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Follower;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ArticleController extends AbstractController
{
	
    /**
    * @Route("/article", name="article")
    */
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        
        return $this->render('articles/index.html.twig', [
            'controller_name' => 'ArticleController', 'user' => $user
        ]);
    }

    /**
     * @Route("/home", name="home")
     * Method({"GET","POST"})
     */
    public function home()
    {
        return $this->render('articles/home.html.twig');
    }

    // /**
    //  * @Route("/", name="twitter")
    //  * Method({"GET","POST"})
    //  */
    // public function post(Request $request)
    // {
    //     $post = new Post();
    //     $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
    //     $form = $this->createFormBuilder($post)
    //         ->add('description', TextareaType::class, [
    //             'required' => false
    //         ])
    //         ->add('attachment', FileType::class, [
    //             'mapped' => false,
    //             'required' => false,
    //             'multiple' => false 
    //         ])
    //         ->add('tweet', SubmitType::class)
    //         ->getForm();
    
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {

    //         $post = $form->getData();
    //         $destination = $this->getParameter('kernel.project_dir').'/public/uploads/posts';
            
    //         $uploadedFile = $form['attachment']->getData();
    //         if($uploadedFile) {
    //             $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME); 
    //             $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

    //             $uploadedFile->move(
    //                 $destination,
    //                 $newFilename
    //             );
    //             $post->setHeaderPic($newFilename);
    //         }
    //         $post->setUser($this->getUser());

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($post); 
    //         $entityManager->flush();
           
            

    //         // do anything else you need here, like send an email

    //         return $this->redirectToRoute('twitter');
    //     }
    //     return $this->render('articles/twitter.html.twig', array
    //         ('form' => $form->createView(), 'user' => $user, 'controller_name' => 'ArticleController')
    //     );
    
    // }

    /**
     * @Route("/profile", name="profile")
     * Method({"GET","POST"})
     */
    public function profile()
    {
        $follower = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);
        $post = $this->getDoctrine()->getRepository(Post::class)->findAll();
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('articles/profile.html.twig', array
            (
                'posts' => $post, 
                'user2' => $user2,
                'follower' => $follower
            )
        );
    }

    /**
     * @Route("/followers", name="follower_list")
     * Method({"GET","POST"})
     */
    public function followers()
    {
        $follower = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);
        $following = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'user' => $this->getUser()
        ]);
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('articles/follower-list.html.twig', array
            (
                'user2' => $user2,
                'follower' => $follower,
                'following' => $following
            )
        );
    }

    /**
     * @Route("/following", name="following_list")
     * Method({"GET","POST"})
     */
    public function following()
    {
        $follower = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);
        $following = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'user' => $this->getUser()
        ]);
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('articles/following-list.html.twig', array
            (
                'user2' => $user2,
                'follower' => $follower,
                'following' => $following
            )
        );
    }

    

    /**
     * @Route("/croping", name="crop")
     * Method({"GET","POST"})
     */
    public function image()
    {
        return $this->render('image-crop/image.html.twig', [
            'controller_name' => 'ArticleController'
        ]);
    }

    
}
