<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Form\TweetType;
use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class TweetController extends AbstractController
{

    #[Route('/tweet/{tweet}/new', name: 'app_tweet_reply')]
    #[Route('/tweet/new', name: 'app_tweet_new')]
    public function reply(Request $request, TweetRepository $repository, #[CurrentUser] ?User $user, ?Tweet $tweet = null)
    {
        $action = $this->generateUrl('app_tweet_new');
        if ($tweet !== null) {
            $action = $this->generateUrl('app_tweet_reply', ['tweet' => $tweet->getId()]);
        }
        $newTweet = new Tweet();
        $newTweet->setAuthor($user)
            ->setParent($tweet);

        $form = $this->createForm(TweetType::class, $newTweet, [
            'action' => $action,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository->save($newTweet, true);
            if ($tweet !== null) {
                return $this->redirectToRoute('app_tweet', ['tweet' => $tweet->getId()]);
            } else {
                return $this->redirect($request->headers->get('referer', '/'));
            }
        }
        return $this->render('tweet/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tweet/{tweet}', name: 'app_tweet')]
    public function index(Tweet $tweet): Response
    {
        return $this->render('tweet/index.html.twig', [
            'tweet' => $tweet,
        ]);
    }


}
