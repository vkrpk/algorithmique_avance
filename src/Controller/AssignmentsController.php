<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

class AssignmentsController extends AbstractController
{
    private array $assignments = [
        ["Maths", 2],
        ["Physics", 2],
        ["Chemistry", 4],
        ["Biology", 2],
        ["History", 1],
        ["Geography", 1],
        ["Literature", 2],
        ["English", 2],
        ["Computer Science", 4],
        ["Economics", 2],
        ["Art", 4],
        ["Music", 1],
        ["Physical Education", 1],
        ["French", 2],
        ["Spanish", 2],
        ["Psychology", 4],
        ["Philosophy", 4],
        ["Political Science", 2],
        ["Sculpture", 2],
        ["Painting", 4]
    ];

    private array $studentCourses = [
        ['Sculpture', 'Painting', 'Music', 'Political Science', 'Art', 'Philosophy', 'Physical Education', 'Spanish'],
        ['Sculpture', 'Painting', 'Spanish', 'Political Science', 'Psychology'],
        ['Psychology', 'Sculpture', 'Spanish', 'Painting', 'French', 'Philosophy', 'Physical Education', 'Art'],
        ['Geography', 'Literature', 'Biology', 'Computer Science', 'Economics', 'Chemistry', 'Physics', 'History'],
        ['Chemistry', 'History', 'Maths', 'Literature', 'Biology', 'Geography', 'English', 'Physics'],
        ['Political Science', 'Music', 'Painting', 'Physical Education', 'Spanish'],
        ['Biology', 'Physics', 'English', 'Chemistry', 'Geography', 'Computer Science', 'Literature', 'Economics', 'History', 'Maths'],
        ['Painting', 'French', 'Psychology', 'Spanish'],
        ['Psychology', 'Music', 'Philosophy', 'Spanish', 'Physical Education', 'French', 'Political Science', 'Painting'],
        ['Music', 'Physical Education', 'Sculpture', 'Political Science', 'Art', 'Painting', 'Philosophy', 'French', 'Spanish'],
        ['Biology', 'History', 'English', 'Maths', 'Economics', 'Computer Science'],
        ['Maths', 'Biology', 'History', 'Geography', 'Physics', 'Literature', 'English', 'Chemistry'],
        ['Literature', 'Maths', 'Computer Science', 'Physics', 'Chemistry', 'Economics', 'History', 'Geography', 'English', 'Biology'],
        ['Sculpture', 'Literature', 'Chemistry', 'Physical Education', 'Physics', 'Art'],
        ['Art', 'Music', 'Sculpture', 'Philosophy', 'Psychology'],
        ['Painting', 'Art', 'Philosophy', 'Political Science', 'Music', 'Physical Education', 'French'],
        ['English', 'Geography', 'Physics', 'Biology'],
        ['Political Science', 'Physical Education', 'Painting', 'Sculpture', 'Spanish', 'Psychology', 'French', 'Music', 'Philosophy', 'Art'],
        ['Painting', 'Art', 'French', 'Spanish', 'Philosophy'],
        ['Political Science', 'Sculpture', 'Spanish', 'Music', 'Psychology'],
        ['Computer Science', 'Biology', 'Physics', 'English', 'History', 'Maths'],
        ['Biology', 'English', 'Chemistry'],
        ['History', 'Economics', 'English', 'Maths', 'Computer Science', 'Biology', 'Geography', 'Chemistry', 'Literature'],
        ['Philosophy', 'Psychology', 'Physical Education', 'Sculpture', 'Spanish'],
        ['History', 'Physics', 'Chemistry', 'Computer Science', 'Economics', 'Literature', 'Biology', 'Maths'],
        ['Painting', 'Psychology', 'Music', 'Physical Education', 'Sculpture', 'Political Science', 'French', 'Spanish', 'Philosophy', 'Art'],
        ['Economics', 'Geography', 'Maths', 'Literature', 'Chemistry', 'Computer Science', 'History', 'English'],
        ['English', 'Geography', 'Economics', 'Biology', 'Physics', 'History', 'Maths', 'Literature'],
        ['French', 'Music', 'Spanish', 'Political Science', 'Painting', 'Philosophy', 'Art', 'Physical Education'],
        ['Painting', 'Political Science', 'Psychology'],
        ['Literature', 'English', 'Economics'],
        ['English', 'Maths', 'Economics', 'Chemistry'],
        ['English', 'Computer Science', 'Geography', 'Biology', 'Economics', 'Physics', 'History', 'Chemistry'],
        ['Chemistry', 'History', 'Literature', 'Maths', 'Economics', 'English', 'Computer Science', 'Geography', 'Biology', 'Physics'],
        ['Political Science', 'Art', 'Sculpture', 'Philosophy', 'Psychology', 'Painting', 'Spanish', 'Physical Education', 'Music', 'French'],
        ['Physics', 'Maths', 'English', 'Biology', 'Literature', 'Economics', 'Computer Science'],
        ['Literature', 'Physics', 'Chemistry', 'Biology', 'Computer Science', 'Economics', 'Geography'],
        ['Physics', 'Literature', 'History', 'English', 'Computer Science', 'Maths'],
        ['Literature', 'Biology', 'Chemistry', 'Geography', 'Maths'],
        ['Biology', 'Music', 'Art', 'Philosophy', 'Physical Education', 'Physics', 'Spanish', 'Maths', 'Economics'],
        ['Psychology', 'French', 'Music'],
        ['French', 'Political Science', 'Spanish', 'Psychology', 'Music'],
        ['French', 'Spanish', 'Philosophy', 'Sculpture', 'Painting', 'Physical Education', 'Psychology'],
        ['Literature', 'Economics', 'Physical Education', 'Sculpture', 'History'],
        ['Chemistry', 'Computer Science', 'Literature', 'Maths', 'Economics', 'Geography', 'History', 'Physics', 'Biology', 'English'],
        ['Psychology', 'Sculpture', 'Spanish', 'French', 'Painting'],
        ['Biology', 'Computer Science', 'Physics', 'Economics', 'Geography'],
        ['Music', 'Physical Education', 'Physics', 'Political Science', 'Painting', 'Literature', 'Spanish'],
        ['French', 'Psychology', 'Political Science', 'Sculpture', 'Spanish', 'Art', 'Philosophy', 'Music', 'Painting'],
        ['Economics', 'Biology', 'English', 'Physics', 'Maths', 'Geography', 'Computer Science', 'Literature', 'History'],
        ['Physical Education', 'Psychology', 'Sculpture', 'Spanish', 'Painting', 'Philosophy'],
        ['Sculpture', 'Music', 'Physical Education', 'Political Science', 'French', 'Spanish', 'Art', 'Philosophy', 'Painting', 'Psychology'],
        ['Philosophy', 'French', 'Music', 'Painting', 'Art'],
        ['Maths', 'Geography', 'Economics'],
        ['Spanish', 'Philosophy', 'Political Science', 'Music', 'Sculpture'],
        ['Economics', 'Maths', 'Biology', 'Computer Science'],
        ['French', 'Psychology', 'Art', 'Sculpture', 'Spanish', 'Physical Education', 'Philosophy', 'Music', 'Painting', 'Political Science'],
        ['Political Science', 'Spanish', 'Physical Education', 'Sculpture', 'Art', 'Psychology', 'Painting'],
        ['Geography', 'Psychology', 'Sculpture', 'Philosophy', 'Music', 'Chemistry', 'Literature', 'Biology', 'Computer Science'],
        ['Biology', 'English', 'History', 'Literature', 'Maths'],
        ['Computer Science', 'English', 'Biology', 'Maths', 'Geography', 'Physics', 'Literature'],
        ['French', 'Political Science', 'Physical Education', 'Psychology', 'Music', 'Art', 'Painting', 'Spanish', 'Sculpture'],
        ['Geography', 'Computer Science', 'History', 'Economics'],
        ['History', 'Music', 'Physics', 'Painting', 'Political Science', 'Literature', 'Computer Science', 'Sculpture', 'Chemistry'],
        ['Maths', 'English', 'Physics', 'Economics', 'Biology', 'Literature', 'Geography', 'Chemistry', 'History', 'Computer Science'],
        ['Economics', 'Computer Science', 'Biology', 'Physics'],
        ['English', 'Geography', 'Chemistry', 'Literature', 'Physics', 'Maths', 'Biology', 'History', 'Economics', 'Computer Science'],
        ['Literature', 'Chemistry', 'History', 'Biology', 'Economics', 'Computer Science', 'Physics'],
        ['Biology', 'Chemistry', 'Geography', 'Maths', 'Literature'],
        ['Sculpture', 'Philosophy', 'Music', 'Psychology', 'Art', 'Physical Education', 'Political Science'],
        ['Maths', 'Literature', 'Chemistry', 'Physics', 'Computer Science'],
        ['Literature', 'Maths', 'Geography', 'Computer Science', 'History', 'Physics', 'Economics', 'Biology'],
        ['History', 'Maths', 'Computer Science'],
        ['Maths', 'History', 'Chemistry', 'Literature', 'Geography'],
        ['Political Science', 'Spanish', 'Sculpture', 'Physical Education', 'Philosophy', 'Painting', 'French', 'Art', 'Music'],
        ['Psychology', 'Art', 'French', 'Philosophy'],
        ['French', 'Music', 'Philosophy'],
        ['Computer Science', 'Maths', 'Literature', 'Economics', 'Physics', 'History', 'English', 'Biology', 'Chemistry'],
        ['English', 'Geography', 'Literature', 'History'],
        ['Literature', 'Maths', 'Computer Science', 'Physics', 'Economics', 'Chemistry'],
        ['English', 'Literature', 'Geography', 'Physics', 'Economics', 'Chemistry', 'Biology'],
        ['Sculpture', 'Spanish', 'Music', 'Philosophy', 'Physical Education', 'Political Science', 'Art', 'Painting', 'Psychology'],
        ['Geography', 'Maths', 'Biology', 'Computer Science'],
        ['Art', 'Painting', 'Spanish', 'Sculpture', 'Music', 'Philosophy', 'Physical Education'],
        ['Literature', 'History', 'Sculpture', 'Music', 'Philosophy', 'Art', 'Painting', 'Economics', 'Biology'],
        ['Painting', 'Sculpture', 'Physical Education', 'Art', 'Music'],
        ['Economics', 'Maths', 'Literature', 'Physics', 'History'],
        ['Painting', 'Philosophy', 'Physical Education', 'Political Science', 'Sculpture', 'Psychology', 'Spanish'],
        ['Psychology', 'Painting', 'Spanish'],
        ['Political Science', 'Music', 'Biology', 'Spanish', 'Physics'],
        ['Philosophy', 'Psychology', 'French', 'Music', 'Spanish', 'Painting'],
        ['Physical Education', 'Art', 'French', 'Psychology', 'Sculpture'],
        ['Biology', 'Geography', 'Literature', 'Physics', 'History'],
        ['English', 'Geography', 'Computer Science', 'Literature', 'Chemistry', 'History', 'Physics', 'Biology', 'Economics', 'Maths'],
        ['History', 'Economics', 'English', 'Chemistry', 'Computer Science', 'Geography', 'Physics', 'Literature', 'Biology', 'Maths'],
        ['Physics', 'Economics', 'Chemistry', 'Geography', 'Biology', 'Literature', 'Computer Science'],
        ['Spanish', 'French', 'Art', 'Painting', 'Political Science', 'Sculpture', 'Philosophy', 'Music'],
        ['Music', 'Physics', 'Sculpture', 'Economics', 'Literature', 'Geography', 'English', 'Computer Science', 'Biology'],
        ['History', 'Economics', 'Literature', 'Physics', 'Maths', 'English', 'Biology'],
        ['Geography', 'English', 'Maths'],
        ['Computer Science', 'Art', 'History', 'Geography', 'Biology', 'Painting', 'Physical Education', 'Philosophy', 'Psychology'],
        ['Economics', 'Literature', 'Maths', 'History', 'Physics', 'Chemistry', 'Geography'],
        ['Painting', 'Psychology', 'French', 'Spanish', 'Physical Education'],
        ['Art', 'Sculpture', 'Physical Education', 'Biology', 'Chemistry', 'Literature', 'Physics', 'Painting', 'Spanish', 'Economics'],
        ['Philosophy', 'Physical Education', 'Sculpture', 'Psychology', 'French', 'Music', 'Art', 'Spanish', 'Political Science'],
        ['Sculpture', 'Spanish', 'Chemistry', 'Physics'],
        ['Art', 'Philosophy', 'Psychology', 'Music', 'Political Science', 'Painting', 'Sculpture', 'Physical Education', 'French'],
        ['History', 'Biology', 'Economics', 'Geography', 'Literature'],
        ['Political Science', 'Spanish', 'Music'],
        ['Physics', 'Maths', 'Literature'],
        ['French', 'Spanish', 'Painting', 'Physical Education', 'Sculpture', 'Psychology', 'Philosophy'],
        ['Physics', 'Literature', 'Geography', 'History', 'Computer Science', 'English', 'Economics'],
        ['Geography', 'Physics', 'Economics', 'Literature', 'English', 'Maths'],
        ['Physical Education', 'Philosophy', 'Political Science', 'French', 'Art', 'Painting', 'Sculpture', 'Spanish', 'Music', 'Psychology'],
        ['Physics', 'Maths', 'Economics', 'History', 'Literature'],
        ['Biology', 'Literature', 'Economics', 'Chemistry', 'Computer Science'],
        ['Philosophy', 'Art', 'Political Science', 'Music', 'Painting', 'French'],
        ['Music', 'Economics', 'Maths', 'Literature', 'Political Science', 'French', 'Art', 'Geography', 'History', 'Psychology'],
        ['Biology', 'Maths', 'Physics', 'Economics', 'Literature'],
        ['Geography', 'Maths', 'History', 'Physics', 'Computer Science', 'English', 'Biology', 'Literature'],
        ['Biology', 'Economics', 'Physics', 'Chemistry', 'English', 'Literature', 'History'],
        ['Philosophy', 'Psychology', 'Physical Education', 'Spanish', 'Music', 'Painting'],
        ['English', 'Economics', 'Physics'],
        ['Philosophy', 'Spanish', 'Art', 'Sculpture'],
        ['Literature', 'Chemistry', 'Geography', 'English', 'Biology', 'Computer Science', 'Economics', 'Maths', 'History', 'Physics'],
        ['Chemistry', 'English', 'History'],
        ['French', 'Painting', 'Sculpture', 'Music', 'Spanish', 'Psychology', 'Political Science', 'Art', 'Physical Education', 'Philosophy'],
        ['French', 'Political Science', 'Music', 'Psychology', 'Art'],
        ['English', 'Literature', 'Biology', 'Geography', 'Economics'],
        ['Music', 'Political Science', 'Philosophy', 'French', 'Sculpture', 'Psychology', 'Spanish'],
        ['Art', 'Philosophy', 'Political Science', 'Physical Education', 'French', 'Psychology', 'Sculpture', 'Spanish'],
        ['Chemistry', 'Maths', 'History', 'Computer Science', 'Biology', 'Literature', 'Economics', 'Geography'],
        ['Philosophy', 'Political Science', 'Physical Education', 'Psychology', 'French', 'Spanish', 'Sculpture', 'Music'],
        ['History', 'Maths', 'Physics', 'English', 'Computer Science'],
        ['Physical Education', 'Political Science', 'Spanish', 'Art', 'Painting', 'Philosophy'],
        ['Political Science', 'Philosophy', 'Physical Education'],
        ['Art', 'Physical Education', 'Painting', 'Sculpture', 'Philosophy', 'Psychology', 'Music'],
        ['Geography', 'Economics', 'Computer Science', 'History'],
        ['Philosophy', 'Psychology', 'Art', 'Physical Education', 'French', 'Political Science', 'Sculpture', 'Music', 'Painting'],
        ['Music', 'Psychology', 'French', 'Painting', 'Political Science', 'Art', 'Sculpture', 'Spanish'],
        ['Geography', 'Economics', 'Computer Science', 'Biology', 'Maths', 'Literature'],
        ['Music', 'Spanish', 'Painting', 'Philosophy', 'Physical Education', 'Sculpture', 'French'],
        ['Spanish', 'Music', 'Sculpture', 'Psychology', 'Physical Education', 'Political Science'],
        ['Music', 'Philosophy', 'Art', 'Psychology', 'Physical Education', 'French', 'Spanish', 'Political Science', 'Painting', 'Sculpture'],
        ['Chemistry', 'Computer Science', 'History', 'Biology', 'Geography', 'English'],
        ['Philosophy', 'Psychology', 'Physical Education', 'French', 'Painting', 'Art', 'Music'],
        ['Psychology', 'Biology', 'Philosophy'],
        ['English', 'Biology', 'Literature', 'Geography', 'Computer Science', 'Economics', 'Physics', 'Chemistry', 'Maths', 'History'],
        ['Painting', 'Art', 'Sculpture'],
        ['Computer Science', 'Art', 'French'],
        ['Economics', 'English', 'Chemistry', 'Literature'],
        ['Music', 'Painting', 'Psychology', 'Philosophy', 'Spanish', 'Art'],
        ['Geography', 'History', 'Economics'],
        ['Economics', 'Physics', 'History', 'Biology', 'Maths', 'Computer Science', 'Chemistry', 'Literature', 'Geography'],
        ['Psychology', 'Sculpture', 'Physical Education', 'Philosophy', 'Political Science', 'Art', 'French'],
        ['Physics', 'Maths', 'Biology', 'English'],
        ['French', 'Spanish', 'Psychology'],
        ['Economics', 'History', 'Maths', 'Biology', 'Geography', 'Chemistry', 'English', 'Literature', 'Computer Science', 'Physics'],
        ['Physical Education', 'Sculpture', 'Painting', 'Spanish', 'Art', 'Psychology'],
        ['Music', 'Philosophy', 'Painting', 'Spanish', 'Sculpture', 'Psychology'],
        ['History', 'Physics', 'Biology', 'Economics', 'Literature'],
        ['Philosophy', 'Art', 'Music', 'Psychology'],
        ['Music', 'Painting', 'Philosophy', 'Political Science', 'Spanish', 'French', 'Physical Education'],
        ['Art', 'Sculpture', 'Physical Education', 'Music', 'Psychology', 'Spanish', 'French'],
        ['Physical Education', 'Political Science', 'French', 'Painting', 'Music', 'Sculpture', 'Psychology', 'Art', 'Philosophy', 'Spanish'],
        ['Physical Education', 'Music', 'Painting', 'Psychology'],
        ['Sculpture', 'Spanish', 'Political Science', 'Physical Education', 'Painting', 'French'],
        ['Biology', 'Maths', 'Physics', 'Computer Science', 'History', 'Economics'],
        ['Biology', 'Maths', 'Chemistry', 'Geography', 'Economics', 'English', 'Physics', 'Computer Science', 'History', 'Literature'],
        ['Biology', 'Economics', 'Computer Science', 'Chemistry', 'English', 'Physics'],
        ['Painting', 'Psychology', 'Music', 'French', 'Physical Education', 'Spanish', 'Art'],
        ['Maths', 'Philosophy', 'Economics', 'Sculpture', 'Spanish', 'Biology', 'Computer Science', 'Physical Education'],
        ['Sculpture', 'Maths', 'Spanish', 'Physical Education', 'Economics', 'Art', 'Philosophy', 'Psychology'],
        ['Sculpture', 'French', 'Painting', 'Political Science', 'Art', 'Music', 'Philosophy', 'Psychology'],
        ['Music', 'Art', 'Biology', 'Chemistry', 'History'],
        ['History', 'English', 'Maths', 'Geography', 'Chemistry', 'Economics', 'Biology'],
        ['Maths', 'Chemistry', 'Economics', 'History', 'Physics', 'Literature', 'Geography', 'English'],
        ['Computer Science', 'Economics', 'Geography', 'Biology', 'History', 'English', 'Chemistry', 'Maths', 'Physics', 'Literature'],
        ['History', 'Biology', 'Chemistry', 'English'],
        ['Literature', 'Economics', 'English', 'Geography', 'Maths', 'Physics', 'History', 'Biology', 'Chemistry'],
        ['French', 'Spanish', 'Art', 'Psychology', 'Sculpture', 'Painting', 'Political Science', 'Philosophy'],
        ['Painting', 'Sculpture', 'French', 'Philosophy', 'Psychology', 'Physical Education'],
        ['Physical Education', 'Music', 'Philosophy', 'Painting', 'Psychology', 'Political Science', 'Art', 'Sculpture', 'French', 'Spanish'],
        ['Computer Science', 'English', 'Geography'],
        ['Music', 'Political Science', 'Sculpture'],
        ['Music', 'Philosophy', 'Political Science', 'Psychology', 'French', 'Painting', 'Sculpture', 'Art', 'Spanish', 'Physical Education'],
        ['Physics', 'English', 'Literature', 'History', 'Computer Science', 'Geography'],
        ['Physics', 'Literature', 'Geography', 'English', 'History', 'Computer Science', 'Biology', 'Maths', 'Chemistry'],
        ['Art', 'Philosophy', 'Spanish', 'Music'],
        ['Chemistry', 'Literature', 'English'],
        ['History', 'Geography', 'Chemistry', 'French', 'Philosophy', 'Painting'],
        ['Psychology', 'Music', 'Painting'],
        ['Computer Science', 'Physics', 'Literature', 'History', 'Chemistry', 'Biology', 'Maths'],
        ['English', 'Computer Science', 'Literature', 'Geography', 'Biology', 'Maths'],
        ['History', 'Economics', 'Geography', 'Physics', 'Biology', 'Maths', 'Computer Science'],
        ['Economics', 'Biology', 'Physics', 'English', 'Geography', 'Computer Science', 'Maths', 'History', 'Literature'],
        ['Computer Science', 'Physics', 'Literature', 'Chemistry', 'History', 'English'],
        ['Literature', 'English', 'History', 'Economics', 'Chemistry', 'Geography', 'Biology'],
        ['English', 'Economics', 'History', 'Geography', 'Chemistry', 'Physics', 'Literature', 'Computer Science'],
        ['Literature', 'Chemistry', 'History', 'Geography', 'English', 'Physics', 'Computer Science', 'Maths', 'Biology', 'Economics'],
        ['Geography', 'Maths', 'English', 'Physics'],
        ['History', 'Spanish', 'Music', 'Philosophy', 'French', 'Psychology', 'Computer Science', 'English'],
        ['Economics', 'Physics', 'History', 'English', 'Biology', 'Maths', 'Literature', 'Chemistry'],
        ['Physics', 'Economics', 'Literature', 'Chemistry', 'History', 'Geography', 'Biology'],
        ['Political Science', 'Philosophy', 'Psychology', 'Painting', 'Music', 'Physical Education', 'Art', 'French', 'Sculpture'],
        ['Geography', 'Maths', 'Computer Science', 'English'],
        ['Political Science', 'French', 'Psychology', 'Painting', 'Spanish', 'Philosophy', 'Sculpture', 'Music', 'Art', 'Physical Education'],
        ['Physical Education', 'Political Science', 'Sculpture', 'Painting', 'French', 'Spanish', 'Music', 'Art'],
        ['French', 'Spanish', 'Music', 'Art', 'Painting', 'Psychology', 'Philosophy'],
        ['Spanish', 'Philosophy', 'Art', 'Painting', 'Political Science', 'Sculpture', 'Music', 'Physical Education', 'Psychology', 'French'],
        ['French', 'Political Science', 'Psychology', 'Physical Education', 'Music', 'Spanish', 'Painting', 'Philosophy'],
        ['Physics', 'Economics', 'English'],
        ['Economics', 'Geography', 'Computer Science', 'Chemistry', 'History', 'Physics'],
        ['English', 'Painting', 'Physical Education', 'Psychology', 'Spanish', 'Philosophy'],
        ['Literature', 'Maths', 'English'],
        ['Psychology', 'Painting', 'Physical Education', 'Art'],
        ['History', 'Maths', 'Physics', 'Economics', 'Geography', 'Literature', 'Computer Science', 'English', 'Biology', 'Chemistry'],
        ['Psychology', 'Sculpture', 'Music', 'Painting', 'Physical Education', 'Art'],
        ['Literature', 'Maths', 'Physics', 'Chemistry', 'History', 'Geography', 'Biology', 'English'],
        ['History', 'Computer Science', 'Geography', 'Biology', 'Chemistry'],
        ['Psychology', 'Physical Education', 'Political Science', 'Spanish', 'Music', 'French', 'Philosophy'],
        ['Music', 'Political Science', 'Physical Education'],
        ['Geography', 'Biology', 'English', 'Chemistry', 'Economics'],
        ['Sculpture', 'Spanish', 'Biology', 'Painting', 'English', 'Art', 'Physical Education', 'Political Science', 'Psychology', 'Philosophy'],
        ['French', 'Sculpture', 'Psychology', 'Art', 'Spanish', 'Painting', 'Physical Education', 'Political Science', 'Music'],
        ['Geography', 'Computer Science', 'History', 'Economics'],
        ['Painting', 'Music', 'Philosophy', 'Psychology'],
        ['Economics', 'History', 'Computer Science', 'Chemistry', 'Maths', 'Biology', 'Geography', 'Physics', 'English', 'Literature'],
        ['Spanish', 'Geography', 'Biology'],
        ['Computer Science', 'Literature', 'English', 'Chemistry'],
        ['Biology', 'Computer Science', 'Literature', 'Physics'],
        ['Biology', 'Chemistry', 'Geography', 'Economics', 'Computer Science', 'English', 'History', 'Maths', 'Literature'],
        ['Economics', 'Spanish', 'Philosophy', 'Music', 'Sculpture', 'French', 'Psychology', 'Maths', 'Geography'],
        ['Literature', 'Biology', 'Maths', 'Geography', 'Computer Science', 'Economics', 'Physics', 'Chemistry'],
        ['Physics', 'Chemistry', 'Maths', 'Geography', 'History', 'Literature', 'Computer Science', 'Economics'],
        ['Psychology', 'Computer Science', 'Sculpture', 'Spanish', 'Philosophy', 'Economics', 'Physical Education'],
        ['Economics', 'Computer Science', 'English'],
        ['Music', 'Art', 'Sculpture'],
        ['Philosophy', 'Sculpture', 'Art', 'French', 'Geography', 'English'],
        ['History', 'Economics', 'Physics', 'Chemistry', 'Literature', 'Maths', 'Computer Science', 'Biology', 'English', 'Geography'],
        ['Maths', 'Literature', 'Philosophy', 'English', 'Spanish'],
        ['French', 'Art', 'Political Science', 'Psychology', 'Painting', 'Philosophy', 'Spanish', 'Physical Education', 'Music', 'Sculpture'],
        ['Art', 'Philosophy', 'Sculpture', 'French'],
        ['Sculpture', 'Political Science', 'Physical Education', 'French', 'Psychology', 'Art', 'Spanish', 'Philosophy', 'Painting'],
        ['Physics', 'Economics', 'Literature', 'Computer Science'],
        ['Biology', 'Computer Science', 'English', 'Economics', 'Physics', 'Literature', 'Maths', 'History', 'Chemistry'],
        ['Philosophy', 'Art', 'Music', 'Psychology', 'Sculpture', 'French', 'Physical Education', 'Spanish'],
        ['Spanish', 'Music', 'Art', 'French', 'Sculpture', 'Painting', 'Political Science', 'Physical Education', 'Psychology'],
        ['Psychology', 'Art', 'Political Science', 'Painting', 'Philosophy', 'Physical Education'],
        ['Maths', 'Economics', 'Chemistry', 'Computer Science', 'Geography'],
    ];


    public function checkCourseName (array $list)
    {
        $typos = [];

        $courses = array_map(function($value) {
            return $value[0];
        }, $this->assignments);

        foreach ($this->studentCourses as $index => $studentCourse) {
            foreach ($studentCourse as $course) {
                if(!in_array($course, $courses)) {
                    $typos[] = $index . ' : ' . $course;
                }
            }
        }

        return $typos;
    }

    private function planifier_examens(array $studentCourses, array $courseList)
    {
        if (!empty($this->checkCourseName($studentCourses))) {
            throw new \RuntimeException('Les noms des cours ne sont pas corrects : ' . implode(', ', $this->checkCourseName($this->studentCourses)));
        }

        $courseCreneau = [];
        $planning = [];

        foreach ($courseList as $course) {
            $courseName = $course[0];
            $courseDuration = $course[1];

            $creneauxInterdits = [];

            foreach ($studentCourses as $studentCourse) {
                if (in_array($courseName, $studentCourse)) {
                    foreach ($studentCourse as $otherCourse) {
                        if ($otherCourse != $courseName && isset($courseCreneau[$otherCourse])) {
                            $creneauxInterdits[] = $courseCreneau[$otherCourse];
                        }
                    }
                }
            }

            $creneau_attribue = 0;
            while (in_array($creneau_attribue, $creneauxInterdits)) {
                $creneau_attribue++;
            }

            $courseCreneau[$courseName] = $creneau_attribue;

            if (!isset($planning[$creneau_attribue])) {
                $planning[$creneau_attribue] = [
                    'courses' => $courseName,
                    'duration' => $courseDuration
                ];
            } else {
                $planning[$creneau_attribue]['courses'] .= '-' . $courseName;
                $planning[$creneau_attribue]['duration'] = max($planning[$creneau_attribue]['duration'], $courseDuration);
            }
        }

        usort($planning, function ($a, $b) {
            return $b['duration'] <=> $a['duration'];
        });

        return $planning;
    }

    function distributeCoursesInDays(array $courses): array {
        $days = [];
        $currentDay = [];
        $hours = 8;

        foreach ($courses as $course) {
            if ($course['duration'] <= $hours) {
                $currentDay[] = $course['courses'];
                $hours -= $course['duration'];
            } else {
                $days[] = $currentDay;
                $currentDay = [$course['courses']];
                $hours = 8 - $course['duration'];
            }
        }

        if (!empty($currentDay)) {
            $days[] = $currentDay;
        }

        return $days;
    }

    #[Route('/assignments', name: 'assignments')]
    public function assignments(): void
    {
        $creneaux = $this->planifier_examens($this->studentCourses, $this->assignments);
        $days = $this->distributeCoursesInDays($creneaux);

        dd($days);
    }
}