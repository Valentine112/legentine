import React, { useEffect, useState } from "react";
import "./css/home.css";
import {
	LineChart,
	Line,
	CartesianGrid,
	XAxis,
	YAxis,
	ResponsiveContainer,
} from "recharts";
import useDash from "../../hooks/useDash";

const Home = () => {
	const dash = useDash();

	const [category, setCategory] = useState({});
	const [userData, setUserData] = useState([]);
	const [postData, setPostData] = useState([]);

	// User chart logic
	useEffect(() => {
		let users = dash.users;

		let dates = Array.from(
			new Set(
				users.map((user) => new Date(user.date.trim()).getFullYear())
			)
		);

		setUserData(
			dates.map((date) => {
				let uData = users.filter(
					(user) => new Date(user.date.trim()).getFullYear() == date
				);

				return {
					name: date,
					uv: uData.length,
					pv: 2400,
					amt: 2400,
				};
			})
		);
	}, []);

	// Post chart logic
	useEffect(() => {
		let posts = dash.posts;

		let dates = Array.from(
			new Set(posts.map((p) => new Date(p.date.trim()).getFullYear()))
		);

		setPostData(
			dates.map((date) => {
				let pData = posts.filter(
					(p) => new Date(p.date.trim()).getFullYear() == date
				);

				return {
					name: date,
					uv: pData.length,
					pv: 2400,
					amt: 2400,
				};
			})
		);
	}, []);


	async function configData(data, type) {
		// Fetch the initial year
		//const data = payload.data.content.post.content
		// Set the users to be used later on this page
		type === "post"
			? setPosts(data)
			: type === "user"
			? setUsers(data)
			: null;

		const dates = [];
		const postCategory = {};

		let promise = new Promise((res) => {
			data.map((v, i) => {
				dates.push(new Date(v.date.trim()).getFullYear());

				if (type === "post") {
					let categ = v.category;
					postCategory[categ] =
						postCategory[categ] == null
							? 1
							: postCategory[categ] + 1;
				}

				// Check if the loop has end to resolve it
				if (i === data.length - 1) res("");
			});
		});

		await promise;
		setCategory(postCategory);

		// First created only unique values
		// This returns an object which is converted to an array and sorted out
		let dateSort = new Set(dates);
		dateSort = Array.from(dateSort).sort();

		// Fetch all the category for posts and get their total
		// count and process the dates
		let count = {};

		dateSort.forEach(async (elem, ind) => {
			// Initialize the data, so it doesn't keep adding data on re-render
			setUserData([]);
			setPostData([]);

			// Counting each seperate elements
			let n = 1;
			const promise = new Promise((res) => {
				dates.forEach((e) => {
					if (e === elem) res((count[elem] = n++));
				});
			});

			await promise;
			// Set the postData
			if (type === "post") {
				setPostData((prev) => [
					...prev,
					{
						name: elem,
						uv: count[elem],
						pv: 2400,
						amt: 2400,
					},
				]);
			}
			// Set the userData
			if (type === "user") {
				setUserData((prev) => [
					...prev,
					{
						name: elem,
						uv: count[elem],
						pv: 2400,
						amt: 2400,
					},
				]);
			}
		});
	}

	return (
		<div className="home container">
			<div className="box-holder row justify-content-around">
				{/* User chart */}
				<div className="boxes col-11 col-md-8 col-lg-10">
					<section className="section-header">
						<p>Users</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={userData}
								>
									<Line
										type="monotone"
										dataKey="uv"
										stroke="#8884d8"
									/>
									<CartesianGrid stroke="#ccc" />
									<XAxis dataKey="name" />
									<YAxis />
								</LineChart>
							</ResponsiveContainer>
						</div>
					</section>
					<div>
						<p>Total users - {users.length}</p>
					</div>
				</div>

				{/* Post chart */}
				<div className="boxes col-11 col-md-8 col-lg-10 mt-3">
					<section className="section-header">
						<p>Posts</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={postData}
								>
									<Line
										type="monotone"
										dataKey="uv"
										stroke="#8884d8"
									/>
									<CartesianGrid stroke="#ccc" />
									<XAxis dataKey="name" />
									<YAxis />
								</LineChart>
							</ResponsiveContainer>
						</div>
					</section>
					<div className="row justify-content-left chart-details">
						<div className="col-12">
							<p>Total post - {posts.length}</p>
						</div>
						{Object.keys(category).map((elem, ind) => (
							<div
								className="col-12 col-md-3 col-lg-2 category"
								key={"category" + ind}
							>
								<p>
									{elem} - {category[elem]}
								</p>
							</div>
						))}
					</div>
				</div>

				<div></div>

				<div className=""></div>

				{/* Active users, Feedbacks,  */}
			</div>
		</div>
	);
};

export default Home;
