import Head from 'next/head'
import {Container, Row, Col, Table, Td} from 'react-bootstrap';
import React, { useState, useEffect } from 'react'
import ReactPaginate from 'react-paginate';
import axios from 'axios';
import Router, { withRouter } from 'next/router'

const About = (props) => {
    const [isLoading, setLoading] = useState(false); //State for the loading indicator
    const startLoading = () => setLoading(true);
    const stopLoading = () => setLoading(false);

    /*
        Posts fetching happens after page navigation,
        so we need to switch Loading state on Router events.
    */
    useEffect(() => { //After the component is mounted set router event handlers
        Router.events.on('routeChangeStart', startLoading);
        Router.events.on('routeChangeComplete', stopLoading);

        return () => {
            Router.events.off('routeChangeStart', startLoading);
            Router.events.off('routeChangeComplete', stopLoading);
        }
    }, [])

    const pagginationHandler = (page) => {
        const currentPath = props.router.pathname;
        const currentQuery = props.router.query;
        currentQuery.page = page.selected + 1;

        props.router.push({
            pathname: currentPath,
            query: currentQuery,
        });
    };
    const changePerPage = (input) =>
    {
        const currentPath = props.router.pathname;
        const currentQuery = props.router.query;
        currentQuery.amount = input.target.value;
        props.router.push({
            pathname: currentPath,
            query: currentQuery,
        });
    }
    return (
        <div className="container">
            <Head>
                <title>BlueFlamingos Opdracht</title>
                <link rel="icon" href="/favicon.ico" />
            </Head>

            <main>
                <h1 className="title">
                    Opdracht voor <a href="https://www.blueflamingos.nl/">BlueFlamingos</a>
                </h1>

                <p className="description">
                    Opdracht van Thijs van der Hoff
                </p>

                <Container fluid>
                    <Table striped bordered hover variant="dark">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Naam</th>
                            <th>EAN</th>
                            <th>Model</th>
                            <th>Categorie</th>
                            <th>Prijs</th>
                        </tr>
                        </thead>
                        <tbody>
                        {props.products.map(prod =>
                            <tr title={prod.shortDescription}>
                                <td>{prod.id}</td>
                                <td>{prod.name}</td>
                                <td>{prod.ean}</td>
                                <td>{prod.model}</td>
                                <td>{prod.categories[0].id}</td>
                                <td>€{prod.prices.filter(price => price.id === prod.pricechanges.reduce((a, b) => {
                                    return new Date(a.date) > new Date(b.date) ? a : b;
                                }).price_id)[0].value}</td>
                            </tr>
                        )}
                        </tbody>
                    </Table>
                    <ReactPaginate
                        previousLabel={'previous'}
                        nextLabel={'next'}
                        breakLabel={'...'}
                        previousClassName={'page-item'}
                        nextClassName={'page-item'}
                        previousLinkClassName={'page-link'}
                        nextLinkClassName={'page-link'}
                        breakClassName={'break-me'}
                        activeClassName={'active'}
                        containerClassName={'pagination'}
                        pageLinkClassName={'page-link'}
                        pageClassName={'page-item'}
                        subContainerClassName={'pages pagination'}

                        initialPage={props.currentPage - 1}
                        pageCount={props.pageCount}
                        marginPagesDisplayed={2}
                        pageRangeDisplayed={5}
                        onPageChange={pagginationHandler}
                    />
                    <select value={props.perPage} onChange={(e)=>changePerPage(e)}>
                        <option>10</option>
                        <option>25</option>
                        <option>50</option>
                        <option>100</option>
                    </select>
                </Container>
            </main>

            <style jsx>{`
        .container {
          min-height: 100vh;
          padding: 0 0.5rem;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
        }

        main {
          padding: 5rem 0;
          flex: 1;
          display: flex;
          flex-direction: column;
          justify-content: center;
          align-items: center;
        }
        
        a {
          color: inherit;
          text-decoration: none;
        }

        .title a {
          color: #0070f3;
          text-decoration: none;
        }

        .title a:hover,
        .title a:focus,
        .title a:active {
          text-decoration: underline;
        }

        .title {
          margin: 0;
          line-height: 1.15;
          font-size: 4rem;
        }

        .title,
        .description {
          text-align: center;
        }

        .description {
          line-height: 1.5;
          font-size: 1.5rem;
        }
      `}</style>
            <style jsx global>{`
        html,
        body {
          padding: 0;
          margin: 0;
          font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto,
            Oxygen, Ubuntu, Cantarell, Fira Sans, Droid Sans, Helvetica Neue,
            sans-serif;
        }

        * {
          box-sizing: border-box;
        }
      `}</style>
        </div>
    );
}

About.getInitialProps = async ({ query }) => {
    const page = query.page || 1; //if page empty we request the first page
    const amount = query.amount || 25;
    const data = await axios.get(`http://127.0.0.1:8000/getProducts?amount=${amount}&page=${page}`);
    return {
        products: data.data.data,
        totalCount: data.data['total'],
        pageCount: data.data['last_page'],
        currentPage: data.data['current_page'],
        perPage: data.data['per_page'],
    };
}

export default withRouter(About);