import React, { useState } from "react";
import "./style.css";
import Sidebar from "../../Components/SideBar/SideBar";
import { BiSearchAlt2, BiSolidShoppingBagAlt } from "react-icons/bi";
import { AiFillHome, AiOutlinePlus, AiOutlineCalendar } from "react-icons/ai";


const Home = () => {
    const items = [
        {
            label: "Home",
            icon: <AiFillHome />,
        },
        {
            label: "Shopping List",
            icon: <BiSolidShoppingBagAlt />,
        },
        {
            label: "Create Recipes",
            icon: <AiOutlinePlus />,
        },
        {
            label: "Create Event",
            icon: <AiOutlinePlus />,
        },
        {
            label: "Calendar",
            icon: <AiOutlineCalendar />,
        },
    ];

    const [searchTerm, setSearchTerm] = useState("");
    const [recipeResults, setRecipehResults] = useState([]);

    const handleSearch = async () => {
        
    };

    return (
        <div className="home">
            <Sidebar items={items} />
            <div className="main-content">
                <div className="search-bar">
                    <input
                        type="text"
                        placeholder="Search recipes..."
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        className="search-input"
                    />
                    {<BiSearchAlt2 className="search-icon" />}
                </div>
                <div className="recipe-results">

                </div>
            </div>
        </div>
    );
};

export default Home;
