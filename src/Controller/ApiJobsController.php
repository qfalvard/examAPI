<?php

namespace App\Controller;

use App\Entity\Job;
use App\Repository\JobRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiJobsController extends AbstractController
{
    public $serializer;

    public function __construct()
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory)];

        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @Route("/jobs", name="jobs", methods={"GET"})
     */
    public function index(JobRepository $jobRepository)
    {

        $jobs = $jobRepository->findAll();


        $data = $this->serializer->normalize($jobs, null, ['groups' => 'all_jobs']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/jobs", name="jobs_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $job = new Job;

        $job->setTitle($request->get('title'));

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($job);

        $manager->flush();

        return new Response('Job ajouté');
    }

    /**
     * @Route("/jobs/show/{job}", name="jobs_show", methods={"GET"}, requirements={"job"="\d+"})
     */
    public function show(Job $job, jobRepository $jobRepository)
    {
        $job = $jobRepository->find($job);

        $data = $this->serializer->normalize($job, null, ['groups' => 'all_jobs']);

        $jsonContent = $this->serializer->serialize($data, 'json');

        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/jobs/edit/{job}", name="edit_jobs", methods={"POST"}, requirements={"job"="\d+"})
     */
    public function edit(Job $job, Request $request)
    {
        if (!empty($request->get('title'))) {
            $job->setTitle($request->get('title'));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($job);
        $em->flush();
        return new Response('Job édité!');
    }

    /**
     * @Route("/jobs/delete/{job}", name="jobs_delete", methods={"DELETE"}, requirements={"job"="\d+"})
     */
    public function delete(Job $job)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($job);
        $em->flush();

        return new Response('eh bim! Job supprimé! Voilà qui est très coronavirus tient!!!');
    }
}
