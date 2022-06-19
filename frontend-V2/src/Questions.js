import React, { useEffect, useState } from 'react';
const Questions = () => {
    // Initialize all tasks into state from backend at component load
    const [questions, setQuestions] = useState([]);
    useEffect(() => {
        fetchSurvey();
    }, []);

    //Fetch All Tasks from Backend
    const fetchSurvey = async () => {
        try {
            const res = await fetch("http://127.0.0.1:8000/api/auth/");
            const data = await res.json();
            const questionsAll = data.data;
            setQuestions(questionsAll);
        } catch (err) {
            console.log(err);
        }
    };


    return (
        <div id="body">
            {questions.length > 0 && questions.map((question, index) => {
                return (
                    <div className="question" key={index}>
                        <div className="question-title" >{question.question}</div>
                        <div className="question-options">
                            {question.option.length > 0 && question.option.map((option, index) => {
                                return (
                                    <div className="option" key={index}>
                                        <div className="option-title">{option.option}</div>
                                        <div className="option-votes">{option.votes}</div>
                                    </div>
                                )
                            })
                            }
                        </div>
                    </div>
                )
            })}
        </div>
    )
}
export default Questions;