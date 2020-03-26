<?php

namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Job;
use App\Repository\EmployeeRepository;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiEmployeesController extends AbstractController
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
     * @Route("/employees", name="employees", methods={"GET"})
     */
    public function index(EmployeeRepository $employeeRepository)
    {
        $employees = $employeeRepository->findAll();

        $data = $this->serializer->normalize($employees, null, ['groups' => 'all_employees']);

        // dd($data);
        
        $jsonContent = $this->serializer->serialize($data, 'json');

        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/employees", name="employee_new", methods={"POST"})
     */
    public function new(Request $request)
    {
        $employee = new Employee;

        $employee->setFirstname($request->get('firstname'));
        $employee->setLastname($request->get('lastname'));
        $test = $request->get('employementDate');
        $this->test = new \DateTime('@' . strtotime($test));
        // dd($this->test);

        $employee->setEmployementDate($this->test);
        // dd($employee);

        $job = $this->getDoctrine()->getRepository(Job::class)->find($request->get('job_id'));

        $employee->setJob($job);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($employee);

        $manager->flush();

        return new Response('Employé ajouté');
    }

    /**
     * @Route("/employees/show/{employee}", name="employee_show", methods={"GET"}, requirements={"employee"="\d+"})
     */
    public function show(Employee $employee, EmployeeRepository $employeeRepository)
    {
        $employee = $employeeRepository->find($employee);

        $data = $this->serializer->normalize($employee, null, ['groups' => 'all_employees']);


        $jsonContent = $this->serializer->serialize($data, 'json');

        $response = new Response($jsonContent);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @Route("/employees/edit/{employee}", name="edit_employees", methods={"POST"}, requirements={"employee"="\d+"})
     */
    public function edit(Employee $employee, Request $request)
    {
        if (!empty($request->get('firstname'))) {
            $employee->setFirstname($request->get('firstname'));
        }
        if (!empty($request->get('lastname'))) {
            $employee->setLastname($request->get('lastname'));
        }
        if (!empty($request->get('job_id'))) {
            $user = $this->getDoctrine()->getRepository(Job::class)->find($request->get('user_id'));
            $employee->setJob($user);
        }
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($employee);
        $em->flush();
        return new Response('Employé édité!');
    }

    /**
     * @Route("/employees/delete/{employee}", name="delete_employes", methods={"DELETE"}, requirements={"employee"="\d+"})
     */
    public function delete(Employee $employee)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($employee);
        $em->flush();

        return new Response('eh bim! Employé supprimé! De la BDD...');
    }
}
