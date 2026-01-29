<?php
/*
public function editAction(Request $request, Car $car)
{
    $editForm = $this->createForm('CarBundle\Form\CarType', $car);
    $editForm->handleRequest($request);
    $brochureDir = $this->getParameter('brochures_directory');
    if (!empty($car->getBrochure()) {
        $car->setBrochure(
               new File($brochureDir . '/' . $car->getBrochure()
        );
    }

    if ($editForm->isSubmitted() && $editForm->isValid()) {
        $file = $car->getBrochure();
        if (!empty($file)) {
            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            // Move the file to the directory where brochures are stored
            $file->move($brochureDir, $fileName);
            $car->setBrochure($fileName);
        }

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return $this->redirectToRoute('car_edit', array('id' => $car->getId()));
    }
} */