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
	const { users, posts } = useDash();

	const categoryKeys = Object.keys(posts.category ?? {});

	return (
		<div className="home">
			<div className="box-holder">
				{/* User chart */}
				<div className="boxes">
					<section className="section-header">
						<p>Users</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={users.timeFrame}
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
						<p>Total users - {users.total}</p>
					</div>
				</div>

				{/* Post chart */}
				<div className="boxes">
					<section className="section-header">
						<p>Posts</p>
					</section>
					<section>
						<div>
							<ResponsiveContainer>
								<LineChart
									width={400}
									height={400}
									data={posts.timeFrame}
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
					<div className="chart-details">
						<div className="col-12">
							<p>Total post - {posts.total}</p>
						</div>
						<div
							className="category"
							key={"category"}
						>
							{categoryKeys.map((key, index) => (
								<div
									className="category"
									key={"category" + index}
								>
									<p>
										{key} - {posts.category[key]}
									</p>
								</div>
							))}
						</div>
					</div>
				</div>

				<div className="">
					<div className=""></div>
				</div>

				{/* Active users, Feedbacks,  */}
			</div>
		</div>
	);
};

export default Home;
