import { DataGrid } from '@mui/x-data-grid';
import React from 'react';
import {Paper} from "@mui/material";

const StatsTable = (props) => {
    const [rows, setRows] = React.useState([]);

    React.useEffect(() => {
        const stats = JSON.parse(props.stats);
        console.log(stats)

        stats.forEach((stat, index) => {
            setRows((prevRows) => [
                ...prevRows,
                {
                    id: index,
                    category: stat.category,
                    resource_type: stat.ressource_type.name,
                    type: stat.type.name,
                    created_at: Date(stat.created_at).toString(),
                }
            ]);
        });
    }, []);



    const columns = [
        { field: 'id', headerName: 'ID', width: 70 },
        { field: 'category', headerName: 'Category', width: 200 },
        { field: 'resource_type', headerName: 'Resource Type', width: 300 },
        { field: 'type', headerName: 'Type', width: 300 },
        { field: 'created_at', headerName: 'Created At', width: 300 },
    ];

    if (rows) {
        return (
            <Paper>
                <DataGrid
                    rows={rows}
                    columns={columns}
                    pageSize={5}
                    rowsPerPageOptions={[5]}
                />
            </Paper>
        )
    } else {
        return <p>Nothing to show</p>
    }
}

export default StatsTable;