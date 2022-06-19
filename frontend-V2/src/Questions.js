import React, { useEffect, useState } from 'react';
import './site.css';
const Questions = ({ reload, setReload, isLoggedIn }) => {
    // Initialize all tasks into state from backend at component load
    const [questions, setQuestions] = useState([]);
    const [userAnswers, setUserAnswers] = useState({});
    useEffect(() => {
        if (reload) {
            fetchSurvey();
        }

    }, [reload]);

    //Fetch All Tasks from Backend
    const fetchSurvey = async () => {
        try {
            const res = await fetch("http://127.0.0.1:8000/api/auth/");
            const data = await res.json();
            const questionsAll = data.data;
            setQuestions(questionsAll);
            setReload(false);
        } catch (err) {
            console.log(err);
        }
    };

    const handleDelete = async (id) => {
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/auth/deleteQuestion?id=${id}`, { method: 'POST' });
            const data = await res.json();
            if (data.status === 'success') {
                setQuestions(questions.filter(question => question.id !== id));
            }
        } catch (err) {
            console.log(err);
        }
    }

    const handleSave = async () => {
        try {
            const res = await fetch(`http://127.0.0.1:8000/api/auth/addAnswer`, {
                method: 'POST',
                body: JSON.stringify({
                    userAnswers
                })
            });
            const data = await res.json();
            debugger
            if (data.status === 'success') {
                setUserAnswers({});
            }
        } catch (err) {
            console.log(err);
        }
    }

    const handleUserAnswer = (id, answer, isCheckBox = false, isChecked = false) => {
        if (isCheckBox) {
            const newUserAnswers = { ...userAnswers };
            if (isChecked) {
                if (newUserAnswers[id]) {
                    newUserAnswers[id].push(answer);
                    setUserAnswers(newUserAnswers);
                } else {
                    newUserAnswers[id] = [answer];
                    setUserAnswers(newUserAnswers);
                }
            } else {
                newUserAnswers[id] = newUserAnswers[id].filter(item => item !== answer);
                setUserAnswers(newUserAnswers);
            }

        } else {
            setUserAnswers({ ...userAnswers, [id]: answer });
        }

    }
    const Options = ({ option, questionType, questionName, questionId }) => {

        if (questionType === 0) {
            return (
                <input type="text" value={userAnswers && userAnswers[questionId]} onBlur={e => handleUserAnswer(questionId, e.currentTarget.value)} />
            )
        }
        if (questionType === 1) {
            return (
                <div className="option-title"><input type="checkbox" checked={userAnswers[questionId]?.length > 0 && userAnswers[questionId].includes(option.option)} onChange={e => { handleUserAnswer(questionId, option.option, true, e.currentTarget.checked) }} />{option.option}</div >
            )
        }
        if (questionType === 2) {

            return (
                <div className="option-title"><input type="radio" name={questionName} checked={userAnswers[questionId] === option.option} onChange={e => { handleUserAnswer(questionId, option.option) }} />{option.option}</div>
            )
        }
    }
    return (
        <div>
            {questions.length > 0 && questions.map((question, index) => {
                const questionType = parseInt(question.type);
                const questionName = question.question;
                const questionId = question.id;
                return (
                    <>
                        <div className="question" key={index}>
                            <div className="question-title" >{questionName}</div>
                            <div className="question-options">
                                {questionType === 3 ?
                                    <select className="type" onChange={(e) => handleUserAnswer(questionId, e.currentTarget.value)} >
                                        {question.option.length > 0 && question.option.map((option, index) => {
                                            return (
                                                <option value={option.option}  > {option.option}</option>
                                            )
                                        })}
                                    </select>

                                    : (
                                        question.option.length > 0 && question.option.map((option, index) => {
                                            return (
                                                <div className="option" key={index}>
                                                    <Options option={option} options={question.option} index={index} questionType={questionType} questionId={questionId} questionName={questionName} />
                                                </div>
                                            )
                                        })
                                    )}

                            </div>
                        {isLoggedIn && <button onClick={() => handleDelete(question.id)}>Delete</button>}
                        </div>
                    </>
                )
            })}
            {!isLoggedIn && <button onClick={handleSave}>Save</button>}
        </div >
    )
}
export default Questions;