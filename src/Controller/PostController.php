<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use App\Entity\Follower;
use App\Entity\Rating;
use App\Form\PostType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PostController extends AbstractController
{
    /**
     * @Route("/", name="twitter")
     */
    public function twitter(Request $request)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        $allpost = $this->getDoctrine()->getRepository(Post::class)->findAll([
            'published' => 'DESC'
        ]);
        $follower = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);
        $follower2 = $this->getDoctrine()->getRepository(Follower::class)->findAll();
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $uploadedFile = $form['attachment']->getData();
            $uploadedFileV = $form['video']->getData();
            if($post->getDescription() OR $uploadedFile OR $uploadedFileV ){
                if($uploadedFile){

                    $destination = $this->getParameter('kernel.project_dir').'/public/setting/uploads/posts';
                    $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                    
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                    $post->setAttachment($newFilename);
                }

                if($uploadedFileV){

                    $destination = $this->getParameter('kernel.project_dir').'/public/setting/uploads/posts';
                    $originalFilename = pathinfo($uploadedFileV->getClientOriginalName(), PATHINFO_FILENAME);
                    $newFilenameV = $originalFilename.'-'.uniqid().'.'.$uploadedFileV->guessExtension();
                    
                    $uploadedFileV->move(
                        $destination,
                        $newFilenameV
                    );
                    $post->setAttachment($newFilenameV);
                }

                $post->setUser($this->getUser());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($post);
                $entityManager->flush();
            }
            

            return $this->redirectToRoute('twitter');
        }
        return $this->render('articles/twitter.html.twig', [
            'form' => $form->createView(), 'user' => $user,
            'user2' => $user2, 'follower' => $follower,
            'follower2' => $follower2,
            'allpost' => $allpost
            
        ]);
        // return $this->render('articles/twitter.html.twig', [
        //     'controller_name' => 'PostController', 'user' => $user
        // ]);
    }

    
    /**
     * @Route("post/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id){

        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($post);
        $entityManager->flush();

        $response = new Response();
        $response->send();
        // $post = $this->getDoctrine()->getRepository
        // (Post::class)->find($id);

        // $entityManager = $this->getDoctrine()->getManager();
        // if(!$post){
        //     throw $this->createNotFoundException(
        //         'No record found for the id : ' . $id
        //     );
        // }
        // $entityManager->remove($post);
        // $entityManager->flush();

        // return $this->redirectToRoute('profile');
    }

    /**
     * @Route("user/follow/{id}", name="follow")
     */
    public function follow(Request $request, $id){
        $follower = new Follower();

        $follower->setFollowedUser($id);
        $follower->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($follower);
        $entityManager->flush();


        return $this->redirectToRoute('twitter');
    }

    /**
     * @Route("user1/follow/{id}")
     */
    public function follow1(Request $request, $id){
        $follower = new Follower();

        $follower->setFollowedUser($id);
        $follower->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($follower);
        $entityManager->flush();


        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("user2/follow/{id}")
     */
    public function follow2(Request $request, $id){
        $follower = new Follower();

        $follower->setFollowedUser($id);
        $follower->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($follower);
        $entityManager->flush();


        return $this->redirectToRoute('profile_update');
    }

    /**
     * @Route("user3/follow/{id}")
     */
    public function follow3(Request $request, $id){
        $follower = new Follower();

        $follower->setFollowedUser($id);
        $follower->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($follower);
        $entityManager->flush();


        return $this->redirectToRoute('follower_list');
    }

    /**
     * @Route("user/unfollow/{id}", name="unfollow")
     */
    public function unfollow(Request $request, $id){
        $follower = $this->getDoctrine()->getRepository
        (Follower::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        if(!$follower){
            throw $this->createNotFoundException(
                'No record found for the id : ' . $id
            );
        }
        $entityManager->remove($follower);
        $entityManager->flush();

        return $this->redirectToRoute('twitter');
    }

    /**
     * @Route("user1/unfollow/{id}")
     */
    public function unfollow1(Request $request, $id){
        $follower = $this->getDoctrine()->getRepository
        (Follower::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        if(!$follower){
            throw $this->createNotFoundException(
                'No record found for the id : ' . $id
            );
        }
        $entityManager->remove($follower);
        $entityManager->flush();

        return $this->redirectToRoute('following_list');
    }

    /**
     * @Route("user2/unfollow/{id}")
     */
    public function unfollow2(Request $request, $id){
        $follower = $this->getDoctrine()->getRepository
        (Follower::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        if(!$follower){
            throw $this->createNotFoundException(
                'No record found for the id : ' . $id
            );
        }
        $entityManager->remove($follower);
        $entityManager->flush();

        return $this->redirectToRoute('follower_list');
    }

    /**
     * @Route("user/like/{id}")
     */
    public function like(Request $request, $id){
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }
        
        
        $like = new Rating();

        $like->setPost($post);
        $like->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($like);
        $entityManager->flush();


        return $this->redirectToRoute('twitter');
    }

    /**
     * @Route("user/unlike/{id}")
     */
    public function unlike(Request $request, $id){
        $like = $this->getDoctrine()->getRepository
        (Rating::class)->findOneBy([
            'user' => $this->getUser(),
            'post' => $id
        ]);

        $entityManager = $this->getDoctrine()->getManager();
        if(!$like){
            throw $this->createNotFoundException(
                'No record found for the id : ' . $id
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($like);
        $entityManager->flush();


        return $this->redirectToRoute('twitter');

    }

    /**
     * @Route("user/retweet/{id}")
     */
    public function retweet(Request $request, $id){
        $post = $this->getDoctrine()->getRepository(Post::class)->find($id);
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }
        
        $tweet = new Post();

        $tweet->setRepost($post);
        $tweet->setUser($this->getUser());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tweet);
        $entityManager->flush();


        return $this->redirectToRoute('twitter');
    }
}
