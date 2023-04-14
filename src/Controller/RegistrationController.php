<?php

/*Importe les modules necessaires*/

namespace App\Controller;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\UserAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Bridge\Google\Transport\GmailSmtpTransport;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bridge\Twig\Mime\BodyRenderer;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;


/*Programmation orientée objet

Classe Enregistrement */

class RegistrationController extends AbstractController
{
   /*Fonction register : fonction qui permet d'enregistrer un nouvel utilisateur*/

    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, 
    UserAuthenticatorInterface $userAuthenticator,  UserAuthenticator $authenticator, 
    EntityManagerInterface $entityManager,  MailerInterface $mailer,
    ): Response

    /*Début*/

    {   // On crée un nouvel objet User
        $user = new User();
        // On appelle le formulaire d'enregistrement
        $form = $this->createForm(RegistrationFormType::class, $user);
        
        $form->handleRequest($request);

        // Si le formulaire est soumis , et valide
          if ($form->isSubmitted() && $form->isValid()) 
         {
                 // Hachage de mot de passe de l'utilisateur
                    $user->setPassword($userPasswordHasher->hashPassword(  $user,
                    $form->get('plainPassword')->getData() ));

                 // Genere un token unique par utilisateur et on l'enregistre
                     $user->setActivationToken(md5(uniqid()));
            
                 // Enregistre le tout dans la base de donnée
                     $entityManager->persist($user);
                     $entityManager->flush();
           
                 // Envoie un email pour que l'utilisateur soit notifié de l'enregistrement
                 // Initiation de l'objet email
                 $mail = $this->getParameter('MAIL');
                 $mailpsw = $this->getParameter('MAILPSW');
                     
                 $transport = new GmailSmtpTransport($mail, $mailpsw);
                    
                     $mailer = new Mailer($transport);
                     $email = (new TemplatedEmail())

                 // Construction de l'objet email
                  ->from('enregistrementsite@gmail.com')
                 // Ajout de l'email du destinataire
                  ->to($user->getUsername())
                 // Ajout du sujet de l'email 
                 ->subject('Enregistrement de votre compte')
                 // Ajout de l'apparence de l'email
                 ->htmlTemplate('email/notification.html.twig')
                ->context([
                'identifiant' => $user->getUsername(),
                'password'=>  $form->get('plainPassword')->getData()])
                ;
                // Pour l'apparence de l'email : 
                // On dit au programme d'aller dans le dossier /templates 
                $loader = new FilesystemLoader('../templates');
                // On appelle twig  qui est une extension  qui gere l'apparence , le code html
                $twig = new Environment($loader);

                $renderer = new BodyRenderer($twig);
                // Associe l'email à l'environnement twig
                $renderer->render($email);  
                //Envoie l'email   
                $mailer->send($email);
        
                 // Retourne l'utilisateur authentifié
                 return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request,
            );
        }
        //Fin du if

        // On retourne sur la page 'enregistrer un nouvel utilisateur'
        return $this->render('admin/registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }


}

   
