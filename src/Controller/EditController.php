<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Post;
use App\Entity\Follower;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\Image;

class EditController extends AbstractController
{
    /**
     * @Route("/edit", name="edit")
     */
    public function index()
    {
        return $this->render('edit/index.html.twig', [
            'controller_name' => 'EditController',
        ]);
    }

     /**
     * @Route("/setting/profile", name="profile_update")
     */
    public function edit(Request $request)
    {   
        $user = new User();
        $user = $this->getDoctrine()->getRepository(User::class)->find($this->getUser());
        $post = $this->getDoctrine()->getRepository(Post::class)->findAll($user);
        $user2 = $this->getDoctrine()->getRepository(User::class)->findAll();
        $follower = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);
        $follower2 = $this->getDoctrine()->getRepository(Follower::class)->findBy([
            'followed_user' => $this->getUser()
        ]);

        $imageConstraints = [
            new Image([
                'maxSize' => '15M'
            ])
        ];
        $form = $this->createFormBuilder($user)
        ->add('name', TextType::class)
        ->add('username', TextType::class)
        ->add('bio', TextareaType::class, [
            'required' => false,
        ])
        ->add('location', TextType::class, [
            'required' => false,
        ])
        ->add('website', UrlType::class, [
            'required' => false,
        ])
        ->add('dob', BirthdayType::class, [
            //1
            //'placeholder' => 'Select a value',
            //2
            // 'placeholder' => [
            //     'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
            // ]
        ])
        ->add('dp', FileType::class, [
            'multiple' => false,
            'mapped'   => false,
            'required' => false,
            'constraints' => $imageConstraints
        ])
        
        ->add('header_pic', FileType::class, [
            'required' => false,
            'multiple' => false,
            'mapped'    => false,
            'constraints' => $imageConstraints
            
        ])
        ->add('save', SubmitType::class)
        ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            /**@var UploadedFile $uploadedFile */
            //dd($form['dp']->getData());
            
            $destination = $this->getParameter('kernel.project_dir').'/public/setting/uploads';

            //Header pic
            $uploadedFile = $form['header_pic']->getData();
            if($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME); 
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setHeaderPic($newFilename);
            }
            
            //for Dp means Profile pic
            $uploadedFile = $form['dp']->getData();
            if($uploadedFile){
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $user->setDp($newFilename);
            }

            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute('profile');
        }
        return $this->render('articles/update.html.twig', array
            ('form' => $form->createView(), 'user' => $user, 
            'posts' => $post,
            'user2' => $user2,
            'follower' => $follower)
        );
    }


}
