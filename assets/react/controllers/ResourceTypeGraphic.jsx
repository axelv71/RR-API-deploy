import React from 'react';
import {Paper, Typography} from "@mui/material";
import {Bar} from "react-chartjs-2";

const ExploitationTypeGraphic = (props) => {
    const labels = ["Image", "Jeu en ligne", "Vidéo", "Article", "Fiche de lecture", "Audio"];
    const [graphData, setGraphData] = React.useState([]);

    React.useEffect(() => {
        const stats = JSON.parse(props.stats);
        console.log(stats)

        let data = []
        labels.forEach((label, index) => {
            let count = 0;
            stats.forEach((stat) => {
                if (stat.ressource_type.name === label) {
                    count++;
                }
            });
            data.push(count);
        });

        setGraphData(data);
        console.log(data)
    }, []);

    const data = {
        labels: labels,
        datasets: [
            {
                label: "Exploitation Type Graphic",
                backgroundColor: "rgb(99,115,255)",
                borderColor: "rgb(99,115,255)",
                data: graphData,
            }
        ]
    }

    return (
        <Paper sx={{ marginY: 2, padding: 2 }}>
            <Typography
                variant="h6"
                component="div"
                sx={{ flexGrow: 1 }}
            >
                Exploitation Type Graphic
            </Typography>
            <Bar data={data} />
        </Paper>
    )
}

export default ExploitationTypeGraphic;